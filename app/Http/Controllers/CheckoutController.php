<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\License;
use App\Models\Purchase;
use App\Services\PayPalService;
use Illuminate\Http\Request;
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
        ]);

        $tiers = $addon->getEffectiveLicenseTiers();
        $tierIndex = max(0, min((int) $request->input('tier_index', 0), count($tiers) - 1));
        $selectedTier = $tiers[$tierIndex] ?? ['label' => 'Standard License', 'quantity' => 1, 'price' => (float) $addon->price];
        $tierPrice = (float) $selectedTier['price'];
        $tierLabel = $selectedTier['label'];
        $quantity = $addon->requires_license ? max(1, (int) ($selectedTier['quantity'] ?? 1)) : 0;

        $totalAmount = $addon->requires_license ? $tierPrice : $addon->price;

        $description = $addon->name . ($addon->requires_license ? ' — ' . $tierLabel . ' (' . $quantity . ' lic.)' : '');

        $returnUrl = route('checkout.success') . '?addon=' . $addon->slug . '&qty=' . $quantity . '&tier=' . urlencode($tierLabel) . '&tier_price=' . $tierPrice;
        $cancelUrl = route('checkout.cancel') . '?addon=' . $addon->slug;

        try {
            $paypal = new PayPalService();
            $order = $paypal->createOrder($description, $totalAmount, $returnUrl, $cancelUrl);

            if (!$order['approval_url']) {
                return back()->with('error', 'Could not connect to PayPal. Please try again.');
            }

            return redirect()->away($order['approval_url']);
        } catch (\Exception $e) {
            return back()->with('error', 'Payment error: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $addon = Addon::where('slug', $request->addon)->firstOrFail();
        $purchase = null;
        $quantity = max(1, (int) $request->input('qty', 1));
        $tierLabel = $request->input('tier', 'Standard License');
        $tierPrice = (float) $request->input('tier_price', $addon->price);
        $paypalOrderId = $request->input('token'); // PayPal sends ?token=ORDER_ID

        if ($paypalOrderId && auth()->check()) {
            try {
                $paypal = new PayPalService();
                $capture = $paypal->captureOrder($paypalOrderId);

                $status = $capture['status'] ?? null;

                if ($status === 'COMPLETED') {
                    $totalAmount = $addon->requires_license ? $tierPrice : $addon->price;

                    $purchase = Purchase::create([
                        'user_id'        => auth()->id(),
                        'addon_id'       => $addon->id,
                        'paypal_order_id' => $paypalOrderId,
                        'amount'         => $totalAmount,
                        'quantity'       => $quantity,
                        'license_tier'   => $addon->requires_license ? $tierLabel : null,
                        'status'         => 'completed',
                        'download_token' => Str::random(64),
                        'expires_at'     => now()->addHours(config('fraxionfx.download_token_expiry_hours', 24)),
                    ]);

                    if ($addon->requires_license) {
                        for ($i = 0; $i < $quantity; $i++) {
                            License::create([
                                'key'        => Str::upper(Str::random(32)),
                                'addon_id'   => $addon->id,
                                'user_id'    => auth()->id(),
                                'purchase_id' => $purchase->id,
                                'status'     => 'active',
                                'is_lifetime' => true,
                            ]);
                        }
                    }
                }
            } catch (\Exception $e) {
                // Payment capture failed — show page without purchase
            }
        }

        if ($purchase) {
            $purchase->load('licenses');
        }

        return view('checkout.success', compact('addon', 'purchase'));
    }

    public function cancel(Request $request)
    {
        $addon = Addon::where('slug', $request->addon)->first();
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
