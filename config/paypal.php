<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode' => env('PAYPAL_MODE', 'sandbox'),

    'sandbox' => [
        'client_id'         => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'client_secret'     => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
    ],

    'live' => [
        'client_id'         => env('PAYPAL_LIVE_CLIENT_ID'),
        'client_secret'     => env('PAYPAL_LIVE_CLIENT_SECRET'),
    ],

    'payment_action' => 'Sale',
    'currency'       => 'GBP',
    'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
    'locale'         => 'en_GB',
    'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating api client.
];
