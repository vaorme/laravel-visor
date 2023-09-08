<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'paypal' => [
        'mode' => env('PAYPAL_MODE'),
        'client_id' => env('PAYPAL_LIVE_CLIENT_ID'),
        'secret' => env('PAYPAL_LIVE_CLIENT_SECRET'),
        'sandbox_client_id' => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'sandbox_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
        'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
        'currency'       => env('PAYPAL_CURRENCY', 'USD'),
        'billing_type'   => 'MerchantInitiatedBilling',
        'notify_url'     => '', // Change this accordingly for your application.
        'locale'         => '', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
        'validate_ssl'   => true, // Validate SSL when creating api client.
    ]
];
