<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\License;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

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
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $addon->name,
                        'description' => Str::limit($addon->description, 200),
                    ],
                    'unit_amount' => (int) ($addon->price * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}&addon=' . $addon->slug,
            'cancel_url' => route('checkout.cancel') . '?addon=' . $addon->slug,
            'metadata' => [
                'addon_id' => $addon->id,
                'user_id' => auth()->id(),
            ],
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $addon = Addon::where('slug', $request->addon)->firstOrFail();
        $purchase = null;

        if ($request->session_id && auth()->check()) {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $session = StripeSession::retrieve($request->session_id);

                $purchase = Purchase::create([
                    'user_id' => auth()->id(),
                    'addon_id' => $addon->id,
                    'stripe_payment_intent_id' => $session->payment_intent,
                    'amount' => $addon->price,
                    'status' => 'completed',
                    'download_token' => Str::random(64),
                    'expires_at' => now()->addHours(config('fraxionfx.download_token_expiry_hours', 24)),
                ]);

                // Create a lifetime license for this paid purchase
                License::create([
                    'key' => Str::upper(Str::random(32)),
                    'addon_id' => $addon->id,
                    'user_id' => auth()->id(),
                    'purchase_id' => $purchase->id,
                    'status' => 'active',
                    'is_lifetime' => true,
                ]);
            } catch (\Exception $e) {
                // Stripe error handled gracefully
            }
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
