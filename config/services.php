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
    'recaptcha' => [
        'sit_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    ],

    // 'google' => [
    //     'client_id' => '590648455847-8j3g2bi5hkj2b4h8c7php8bv4io380sm.apps.googleusercontent.com',
    //     'client_secret' => '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',
    //     'redirect' => 'http://127.0.0.1:8000/auth/google/callback',
    // ],
    // 'facebook' => [
    //     'client_id' => '906697077590239',
    //     'client_secret' => '6c3fdd2cc71189c54a84b6ea8168e33c',
    //     'redirect' => 'http://localhost:8000/auth/facebook/callback'
    // ],
    'paymob' => [
        'api_key' => 'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2T1RZMU9EZzJMQ0p1WVcxbElqb2lhVzVwZEdsaGJDSjkuNUVWeHNUSHg3Q05tN3ZKR1BpQ2FfVjVwN2VpXzNnY0xvRUV1cGx6RGdiSURSb1N6RVY2b0dDY0hXczBCeEs2OWlvU2JiSllZVTduZ2pfdVV4czFlUVE=',
        'integration_id' => '4539895',
        'iframe_id' => '832385',
        'currency' => 'EGP', 
        'hmac' => '52FECA5EB581D5FED6D754FC7EC1ABD8',
    ],

];
