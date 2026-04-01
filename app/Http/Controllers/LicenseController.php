<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Models\License;
use Carbon\Carbon;

class LicenseController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function validate(Request $request)
    {
        $validated = $request->validate([
            'license_key' => 'required|string',
            'addon_slug'  => 'nullable|string', // from website/new clients
            'addon_id'    => 'nullable|string', // from Blender plugin
            'machine_id'  => 'nullable|string',
            'version'     => 'nullable|string',
            'platform'    => 'nullable|string',
        ]);

        // Accept either addon_slug or addon_id
        $addonSlug = $validated['addon_slug'] ?? $validated['addon_id'] ?? null;

        if (!$addonSlug) {
            return response()->json([
                'valid' => false,
                'error' => 'addon_slug is required.',
            ], 422);
        }

        // Normalize: underscores → hyphens, lowercase, trim (matches Laravel slugs)
        $addonSlug = str_replace('_', '-', strtolower(trim($addonSlug)));

        try {
            // Find license by key (case-insensitive) and addon slug
            $license = License::whereRaw('UPPER(key) = ?', [strtoupper(trim($validated['license_key']))])
                ->whereHas('addon', fn ($q) => $q->where('slug', $addonSlug))
                ->first();

            if (!$license) {
                $keyExists = License::whereRaw('UPPER(key) = ?', [strtoupper(trim($validated['license_key']))])->exists();

                return response()->json([
                    'valid' => false,
                    'error' => $keyExists
                        ? 'License key does not match the given addon.'
                        : 'License key not found.',
                    'message' => 'Invalid license key',
                ], 200);
            }

            if ($license->status !== 'active') {
                return response()->json([
                    'valid' => false,
                    'error' => 'License is not active.',
                    'status' => $license->status
                ], 200);
            }

            // Single-user enforcement: bind to first machine, reject others
            if ($license->machine_id && $license->machine_id !== $validated['machine_id']) {
                return response()->json([
                    'valid' => false,
                    'error' => 'This license is already activated on another device.',
                    'message' => 'License bound to a different machine'
                ], 200);
            }

            // Bind to this machine on first use
            if (!$license->machine_id) {
                $license->update(['machine_id' => $validated['machine_id']]);
            }

            $addon = $license->addon;

            // For free addons (lifetime by default)
            if ($addon->price <= 0 || $license->is_lifetime) {
                return response()->json([
                    'valid' => true,
                    'license_type' => $addon->price <= 0 ? 'free' : 'lifetime',
                    'addon_slug' => $addon->slug,
                    'addon_name' => $addon->name,
                    'checked_at' => now()->toIso8601String(),
                    'message' => 'License validated successfully'
                ], 200);
            }

            // For subscription-based licenses, check Stripe
            if ($license->stripe_subscription_id) {
                try {
                    $subscription = \Stripe\Subscription::retrieve(
                        $license->stripe_subscription_id
                    );

                    if ($subscription->status === 'active') {
                        return response()->json([
                            'valid' => true,
                            'license_type' => 'subscription',
                            'addon_slug' => $addon->slug,
                            'addon_name' => $addon->name,
                            'expires_at' => Carbon::createFromTimestamp(
                                $subscription->current_period_end
                            )->toIso8601String(),
                            'checked_at' => now()->toIso8601String(),
                            'message' => 'Subscription license is active'
                        ], 200);
                    }

                    return response()->json([
                        'valid' => false,
                        'error' => 'Subscription is not active.',
                        'subscription_status' => $subscription->status
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json([
                        'valid' => false,
                        'error' => 'Could not verify subscription with payment provider.'
                    ], 200);
                }
            }

            // Check expiration date if set
            if ($license->expires_at && $license->expires_at->isPast()) {
                return response()->json([
                    'valid' => false,
                    'error' => 'License has expired.',
                    'expired_at' => $license->expires_at->toIso8601String()
                ], 200);
            }

            // License is valid
            return response()->json([
                'valid' => true,
                'addon_slug' => $addon->slug,
                'addon_name' => $addon->name,
                'checked_at' => now()->toIso8601String(),
                'message' => 'License validated successfully'
            ], 200);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('License validate 500', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return response()->json([
                'valid'  => false,
                'error'  => 'Unexpected error occurred.',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }
}
