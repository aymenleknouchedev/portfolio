<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private string $mode;

    public function __construct()
    {
        // Credentials are intentionally locked to environment configuration.
        // They are NOT readable from the Setting model so the admin panel
        // cannot override the live PayPal account in use.
        $this->mode         = config('services.paypal.mode', 'sandbox');
        $this->clientId     = trim((string) config('services.paypal.client_id', ''));
        $this->clientSecret = trim((string) config('services.paypal.client_secret', ''));

        $this->baseUrl = $this->mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        if ($this->clientId === '' || $this->clientSecret === '') {
            throw new \RuntimeException('PayPal is not configured. Set PAYPAL_CLIENT_ID and PAYPAL_CLIENT_SECRET in the .env file.');
        }
    }

    /**
     * Cache the access token for 8 hours (PayPal tokens are valid ~9h).
     * Cache key is namespaced by mode + a short hash of the client id so swapping
     * sandbox/live or rotating credentials immediately picks up new ones.
     */
    private function getAccessToken(): string
    {
        $cacheKey = 'paypal_token_' . $this->mode . '_' . substr(sha1($this->clientId), 0, 10);

        return Cache::remember($cacheKey, now()->addHours(8), function () {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->timeout(15)
                ->post("{$this->baseUrl}/v1/oauth2/token", ['grant_type' => 'client_credentials']);

            if (!$response->successful()) {
                Log::error('PayPal auth failed', ['status' => $response->status(), 'body' => $response->body()]);
                throw new \RuntimeException('PayPal authentication failed. Please verify your Client ID and Secret in Settings → Payment.');
            }

            $token = $response->json('access_token');

            if (!$token) {
                throw new \RuntimeException('PayPal returned no access token. Check your Client ID and Secret in Settings → Payment.');
            }

            return $token;
        });
    }

    public function createOrder(string $description, float $amount, string $returnUrl, string $cancelUrl, ?string $customId = null): array
    {
        $token = $this->getAccessToken();

        $purchaseUnit = [
            'description' => mb_substr($description, 0, 127),
            'amount' => [
                'currency_code' => 'USD',
                'value' => number_format($amount, 2, '.', ''),
            ],
        ];

        if ($customId !== null) {
            // custom_id is echoed back on capture — useful for cross-checking
            $purchaseUnit['custom_id'] = mb_substr($customId, 0, 127);
        }

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [$purchaseUnit],
            'application_context' => [
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
                'brand_name'  => mb_substr((string) config('app.name'), 0, 127),
                'user_action' => 'PAY_NOW',
                'shipping_preference' => 'NO_SHIPPING',
            ],
        ];

        $response = Http::withToken($token)
            ->timeout(20)
            ->post("{$this->baseUrl}/v2/checkout/orders", $payload);

        Log::info('PayPal create order', [
            'mode'     => $this->mode,
            'status'   => $response->status(),
            'amount'   => $payload['purchase_units'][0]['amount'],
            'return'   => $returnUrl,
            'cancel'   => $cancelUrl,
            'response' => $response->json() ?: $response->body(),
        ]);

        if (!$response->successful()) {
            Log::error('PayPal create order failed', ['status' => $response->status(), 'body' => $response->body()]);
            throw new \RuntimeException('Could not create PayPal order. Please try again.');
        }

        $data = $response->json();

        $approvalUrl = collect($data['links'] ?? [])
            ->firstWhere('rel', 'approve')['href'] ?? null;

        return [
            'id'           => $data['id'] ?? null,
            'approval_url' => $approvalUrl,
        ];
    }

    /**
     * Capture an approved order and return a normalized result containing
     * the captured status, gross amount, currency and raw payload.
     */
    public function captureOrder(string $orderId): array
    {
        $token = $this->getAccessToken();

        // PayPal /capture requires a JSON body. An empty body or wrong
        // content-type causes silent failures. We send `{}` explicitly.
        $response = Http::withToken($token)
            ->timeout(20)
            ->withBody('{}', 'application/json')
            ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture");

        $data = is_array($response->json()) ? $response->json() : [];

        // Always log the raw capture response — silent failures here are the
        // single most common cause of "payment success but no money" reports.
        Log::info('PayPal capture response', [
            'order_id' => $orderId,
            'status'   => $response->status(),
            'body'     => $data,
        ]);

        $status = $data['status'] ?? null;
        $alreadyCaptured = ($data['details'][0]['issue'] ?? null) === 'ORDER_ALREADY_CAPTURED';

        // If already captured re-fetch order to get the actual captured amount
        if ($alreadyCaptured) {
            $details = $this->getOrder($orderId);
            $data = $details ?: $data;
            $status = $data['status'] ?? 'COMPLETED';
        }

        $captured = $status === 'COMPLETED' || $alreadyCaptured;

        $captureNode = $data['purchase_units'][0]['payments']['captures'][0] ?? null;
        $unitAmount  = $data['purchase_units'][0]['amount'] ?? null;

        $grossAmount = $captureNode['amount']['value']
            ?? $unitAmount['value']
            ?? null;
        $currency = $captureNode['amount']['currency_code']
            ?? $unitAmount['currency_code']
            ?? null;
        $customId = $captureNode['custom_id']
            ?? ($data['purchase_units'][0]['custom_id'] ?? null);

        if (!$captured) {
            Log::warning('PayPal capture not completed', [
                'order_id' => $orderId,
                'status'   => $status,
                'response' => $data,
            ]);
        }

        return [
            'captured'     => $captured,
            'status'       => $status,
            'gross_amount' => $grossAmount !== null ? (float) $grossAmount : null,
            'currency'     => $currency,
            'custom_id'    => $customId,
            'raw'          => $data,
        ];
    }

    public function getOrder(string $orderId): ?array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->timeout(15)
            ->get("{$this->baseUrl}/v2/checkout/orders/{$orderId}");

        if (!$response->successful()) {
            return null;
        }

        return $response->json();
    }
}
