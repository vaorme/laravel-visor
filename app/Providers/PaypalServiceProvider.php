<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class PaypalServiceProvider extends ServiceProvider
{
    private $apiBase;
    private $clientId;
    private $secret;

    public function __construct(){
        $this->apiBase = config('services.paypal.mode') === 'live'
            ? 'https://api.paypal.com/v2'
            : 'https://api-m.sandbox.paypal.com/v2';
        $this->clientId = config('services.paypal.mode') === 'live'
            ? config('services.paypal.client_id')
            : config('services.paypal.sandbox_client_id');
        $this->secret = config('services.paypal.mode') === 'live'
            ? config('services.paypal.secret')
            : config('services.paypal.sandbox_secret');
    }
    public function boot(): void{}
    public function createOrder($amount, $reference_id, $successUrl, $cancelUrl){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ])->post($this->apiBase . '/checkout/orders', [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    "reference_id" => $reference_id,
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $amount,
                    ],
                ],
            ],
            'application_context' => [
                'return_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ],
        ]);

        return $response->json();
    }
    public function capturePaymentOrder($token){
        $accessToken = $this->getAccessToken();
        $mode = config('services.paypal.mode');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post("https://api-m.sandbox.paypal.com/v2/checkout/orders/{$token}/capture", [
            'return' => 'representation'
        ]);
        // $response = Http::withToken($accessToken)
        // ->contentType('application/json')
        // ->send('POST',$this->apiBase.'/checkout/orders/'.$token.'/capture')
        // ->created();
        return $response->json();
    }

    private function getAccessToken(){
        $clientId = $this->clientId;
        $secret = $this->secret;
        $mode = config('services.paypal.mode');

        $credentials = base64_encode($clientId . ':' . $secret);

        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Basic ' . $credentials,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->post("https://api-m.$mode.paypal.com/v1/oauth2/token", [
            "grant_type" => 'client_credentials',
        ]);

        return $response->json('access_token');
    }
}
