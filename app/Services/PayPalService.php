<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class PayPalService
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;

    public function __construct()
    {
        $mode = Setting::get('paypal_mode') ?: config('services.paypal.mode', 'sandbox');
        $this->baseUrl = $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
        $this->clientId     = Setting::get('paypal_client_id') ?: config('services.paypal.client_id', '');
        $this->clientSecret = Setting::get('paypal_client_secret') ?: config('services.paypal.client_secret', '');
    }

    private function getAccessToken(): string
    {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post("{$this->baseUrl}/v1/oauth2/token", ['grant_type' => 'client_credentials']);

        if (!$response->successful()) {
            throw new \RuntimeException('PayPal authentication failed: ' . $response->status() . ' — ' . $response->body());
        }

        $token = $response->json('access_token');

        if (!$token) {
            throw new \RuntimeException('PayPal returned no access token. Check your Client ID and Secret in Settings → Payment.');
        }

        return $token;
    }

    public function createOrder(string $description, float $amount, string $returnUrl, string $cancelUrl): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'description' => $description,
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($amount, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                    'brand_name'  => config('app.name'),
                    'user_action' => 'PAY_NOW',
                ],
            ]);

        $data = $response->json();

        $approvalUrl = collect($data['links'] ?? [])
            ->firstWhere('rel', 'approve')['href'] ?? null;

        return [
            'id'           => $data['id'] ?? null,
            'approval_url' => $approvalUrl,
        ];
    }

    public function captureOrder(string $orderId): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture");

        return $response->json();
    }
}
