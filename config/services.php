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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'square' => [
    'sandbox_application_id' => env('SQUARE_SANDBOX_APP_ID'),
    'sandbox_access_token' => env('SQUARE_SANDBOX_ACCESS_TOKEN'),
    'sandbox_location_id' => env('SQUARE_SANDBOX_LOCATION_ID'),

    'live_application_id' => env('SQUARE_LIVE_APP_ID'),
    'live_access_token' => env('SQUARE_LIVE_ACCESS_TOKEN'),
    'live_location_id' => env('SQUARE_LIVE_LOCATION_ID'),

    'mode' => env('SQUARE_MODE', 'sandbox'),
    ],

    'paypal' => [
        'mode' => env('PAYPAL_MODE', 'sandbox'),

        'sandbox_client_id' => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'sandbox_client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
        
        'live_client_id' => env('PAYPAL_LIVE_CLIENT_ID'),
        'live_client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET'),
    ],


];
