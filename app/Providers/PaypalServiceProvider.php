<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class PaypalServiceProvider extends ServiceProvider{
    private $apiUrl;
    private $oauthUrl;
    private $clientId;
    private $secret;

    public function __construct(){
        $this->apiUrl = config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com/v2'
            : 'https://api-m.sandbox.paypal.com/v2';
        $this->oauthUrl = config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com/v1'
            : 'https://api-m.sandbox.paypal.com/v1';
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
        ])->post($this->apiUrl . '/checkout/orders', [
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
            ]
        ]);

        return $response->json();
    }
    public function orderDetails($token){
        $accessToken = $this->getAccessToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->get($this->apiUrl ."/checkout/orders/{$token}");
        return $response->json();
    }
    public function capturePaymentOrder($token){
        $accessToken = $this->getAccessToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl ."/checkout/orders/{$token}/capture", [
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

        $credentials = base64_encode($clientId . ':' . $secret);

        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Basic ' . $credentials,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->post($this->oauthUrl ."/oauth2/token", [
            "grant_type" => 'client_credentials',
        ]);
        return $response->json('access_token');
    }
}
