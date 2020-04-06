<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => PockDoc\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'kazinfoteh' => [
        'username' => env('KAZINFOTEH_USERNAME'),
        'password' => env('KAZINFOTEH_PASSWORD'),
        'originator' => env('KAZINFOTEH_ORIGINATOR'),
    ],

    'cloudpayments' => [
        'public_key' => env('CP_PUBLIC_KEY'),
        'private_key' => env('CP_PRIVATE_KEY'),
    ],

    'rollbar' => [
        'access_token' => env('ROLLBAR_ACCESS_TOKEN'),
        'level' => 'error',
    ],

    'fcm' => [
        'key' => env('FCM_API_KEY')
    ],

];
