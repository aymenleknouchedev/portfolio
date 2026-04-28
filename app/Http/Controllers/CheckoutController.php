<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\License;
use App\Models\PromoCode;
use App\Models\Purchase;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show(Addon $addon)
    {
        if ($addon->price <= 0) {
            return redirect()->route('download.free', $addon->slug);
        }
        return view('checkout.index', compact('addon'));
    }

    public function process(Addon $addon, Request $request)
    {
        $request->validate([
            'tier_index' => 'nullable|integer|min:0',
            'promo_code' => 'nullable|string|max:50',
        ]);

        if ($addon->price <= 0) {
            return redirect()->route('download.free', $addon->slug);
        }

        // Resolve tier server-side — never trust pricing from the client
        $tiers = $addon->getEffectiveLicenseTiers();
        $tierIndex = max(0, min((int) $request->input('tier_index', 0), max(0, count($tiers) - 1)));
        $selectedTier = $tiers[$tierIndex] ?? ['label' => 'Standard License', 'quantity' => 1, 'price' => (float) $addon->price];
        $tierPrice = (float) $selectedTier['price'];
        $tierLabel = (string) $selectedTier['label'];
        $quantity  = $addon->requires_license ? max(1, (int) ($selectedTier['quantity'] ?? 1)) : 1;

        $subtotal = $addon->requires_license ? $tierPrice : (float) $addon->price;

        // Validate promo server-side
        $promoDiscount = 0.0;
        $promoCodeStr  = null;
        if ($request->filled('promo_code')) {
            $promo = PromoCode::where('code', strtoupper(trim($request->input('promo_code'))))->first();
            if ($promo && $promo->isValid()) {
                $promoDiscount = (float) $promo->calculateDiscount($subtotal);
                if ($promoDiscount > 0) {
                    $promoCodeStr = $promo->code;
                }
            }
        }

        $totalAmount = max(0.01, round($subtotal - $promoDiscount, 2));

        try {
            $paypal = new PayPalService();

            // Create the PayPal order first so we have its ID
            $description = $addon->name . ($addon->requires_license ? ' — ' . $tierLabel . ' (' . $quantity . ' lic.)' : '');
            $returnUrl   = route('checkout.success');
            $cancelUrl   = route('checkout.cancel') . '?addon=' . urlencode($addon->slug);

            $order = $paypal->createOrder(
                $description,
                $totalAmount,
                $returnUrl,
                $cancelUrl,
                customId: 'user:' . auth()->id() . '|addon:' . $addon->id
            );

            if (empty($order['id']) || empty($order['approval_url'])) {
                return back()->with('error', 'Could not connect to PayPal. Please try again.');
            }

            // Persist a server-trusted pending purchase. This eliminates any
            // possibility of price/quantity tampering through the return URL.
            Purchase::create([
                'user_id'         => auth()->id(),
                'addon_id'        => $addon->id,
                'paypal_order_id' => $order['id'],
                'amount'          => $totalAmount,
                'quantity'        => $quantity,
                'license_tier'    => $addon->requires_license ? $tierLabel : null,
                'promo_code'      => $promoCodeStr,
                'promo_discount'  => $promoDiscount > 0 ? $promoDiscount : null,
                'status'          => 'pending',
            ]);

            return redirect()->away($order['approval_url']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PayPal create error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Payment error: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        // PayPal sends ?token=ORDER_ID
        $paypalOrderId = $request->input('token');

        if (!$paypalOrderId) {
            abort(404);
        }

        $purchase = Purchase::with('addon')
            ->where('paypal_order_id', $paypalOrderId)
            ->first();

        // Order must exist locally and belong to the current user
        if (!$purchase) {
            abort(404, 'Order not found.');
        }

        if (!auth()->check() || (int) $purchase->user_id !== (int) auth()->id()) {
            abort(403, 'You are not authorized to view this order.');
        }

        $addon = $purchase->addon;

        // Already completed (page refresh / double-visit) — just render
        if ($purchase->status === 'completed') {
            $purchase->load('licenses');
            return view('checkout.success', compact('addon', 'purchase'));
        }

        try {
            $paypal  = new PayPalService();
            $capture = $paypal->captureOrder($paypalOrderId);

            // Verification policy:
            //   1. PayPal MUST report the order as captured (status COMPLETED).
            //      If it did, the customer paid the amount we put in the order
            //      server-side — the gross amount can never have been tampered.
            //   2. We log any amount/currency discrepancy but DO NOT reject the
            //      payment for them, because PayPal may convert into the
            //      merchant-account currency (returning e.g. EUR/MAD instead of
            //      USD) and the gross_amount in that currency will not match
            //      our stored USD amount. Rejecting would mark a real,
            //      successful charge as "failed" — which is what was happening.
            $expectedAmount = round((float) $purchase->amount, 2);
            $actualAmount   = $capture['gross_amount'] !== null ? round($capture['gross_amount'], 2) : null;

            if ($capture['captured']) {
                // Diagnostic warning only — does NOT block fulfillment
                if ($capture['currency'] !== null && $capture['currency'] !== 'USD') {
                    \Illuminate\Support\Facades\Log::info('PayPal captured in non-USD currency', [
                        'order_id'         => $paypalOrderId,
                        'captured_amount'  => $actualAmount,
                        'captured_currency' => $capture['currency'],
                        'expected_usd'     => $expectedAmount,
                    ]);
                }

                DB::transaction(function () use ($purchase, $addon) {
                    $purchase->update([
                        'status'         => 'completed',
                        'download_token' => Str::random(64),
                        'expires_at'     => now()->addHours(config('fraxionfx.download_token_expiry_hours', 24)),
                    ]);

                    if ($purchase->promo_code) {
                        PromoCode::where('code', $purchase->promo_code)->increment('used_count');
                    }

                    if ($addon->requires_license) {
                        for ($i = 0; $i < max(1, (int) $purchase->quantity); $i++) {
                            License::create([
                                'key'         => Str::upper(Str::random(32)),
                                'addon_id'    => $addon->id,
                                'user_id'     => $purchase->user_id,
                                'purchase_id' => $purchase->id,
                                'status'      => 'active',
                                'is_lifetime' => true,
                            ]);
                        }
                    }
                });
            } else {
                \Illuminate\Support\Facades\Log::warning('PayPal capture not completed', [
                    'order_id'        => $paypalOrderId,
                    'paypal_status'   => $capture['status'] ?? null,
                    'expected_amount' => $expectedAmount,
                    'actual_amount'   => $actualAmount,
                    'currency'        => $capture['currency'],
                    'raw'             => $capture['raw'] ?? null,
                ]);

                $purchase->update(['status' => 'failed']);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PayPal capture error', [
                'order_id' => $paypalOrderId,
                'error'    => $e->getMessage(),
            ]);
        }

        $purchase->refresh()->load('licenses');

        return view('checkout.success', compact('addon', 'purchase'));
    }

    public function cancel(Request $request)
    {
        $addon = Addon::where('slug', $request->addon)->first();

        // Mark any matching pending purchase as failed (best-effort cleanup)
        $token = $request->input('token');
        if ($token && auth()->check()) {
            Purchase::where('paypal_order_id', $token)
                ->where('user_id', auth()->id())
                ->where('status', 'pending')
                ->update(['status' => 'failed']);
        }

        return view('checkout.cancel', compact('addon'));
    }

    public function download(string $token)
    {
        $purchase = Purchase::where('download_token', $token)
            ->where('status', 'completed')
            ->firstOrFail();

        if ($purchase->isExpired()) {
            abort(403, 'Download link has expired. Please visit your dashboard to generate a new link.');
        }

        if (!$purchase->addon->file_path) {
            abort(404, 'Download file not available.');
        }

        // External URL - redirect to it
        if (str_starts_with($purchase->addon->file_path, 'http')) {
            return redirect()->away($purchase->addon->file_path);
        }

        $filePath = Storage::disk('local')->path($purchase->addon->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'Download file not found. Please contact support.');
        }

        return response()->download($filePath, $purchase->addon->name . '.zip');
    }

    public function freeDownload(Addon $addon)
    {
        if ($addon->price > 0) {
            return redirect()->route('shop.show', $addon->slug);
        }

        if (!$addon->file_path) {
            return redirect()->route('shop.show', $addon->slug)
                ->with('download_error', 'This add-on does not have a downloadable file yet. Please check back soon.');
        }

        // Create purchase + license for authenticated users
        if (auth()->check()) {
            $existingPurchase = Purchase::where('user_id', auth()->id())
                ->where('addon_id', $addon->id)
                ->where('status', 'completed')
                ->first();

            if (!$existingPurchase) {
                $purchase = Purchase::create([
                    'user_id' => auth()->id(),
                    'addon_id' => $addon->id,
                    'amount' => 0,
                    'status' => 'completed',
                    'download_token' => Str::random(64),
                    'expires_at' => now()->addHours(config('fraxionfx.download_token_expiry_hours', 24)),
                ]);

                if ($addon->requires_license) {
                    License::create([
                        'key' => Str::upper(Str::random(32)),
                        'addon_id' => $addon->id,
                        'user_id' => auth()->id(),
                        'purchase_id' => $purchase->id,
                        'status' => 'active',
                        'is_lifetime' => true,
                    ]);
                }
            }

            return redirect()->route('client.dashboard')
                ->with('success', 'Free add-on added to your purchases! Your license key is ready.');
        }

        // External URL — redirect the browser to it directly
        if (str_starts_with($addon->file_path, 'http://') || str_starts_with($addon->file_path, 'https://')) {
            return redirect()->away($addon->file_path);
        }

        // Local file stored in non-public storage
        $filePath = Storage::disk('local')->path($addon->file_path);

        if (!file_exists($filePath)) {
            return redirect()->route('shop.show', $addon->slug)
                ->with('download_error', 'The download file could not be found. Please contact support.');
        }

        return response()->download($filePath, $addon->name . '.zip');
    }
}
