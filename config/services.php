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

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // Alamat Sistem Survey (Kiosk) yang sudah dipisah, dipakai untuk
    // tombol "Buka Kiosk" dan generate QR code di halaman /qrcode
    'kiosk' => [
        'url' => env('KIOSK_URL', 'http://localhost:8000'),
    ],

    // konfigurasi lain...

    'cv' => [

        /*
         * Token yang harus sama dengan token
         * pada sistem Python Computer Vision.
         */
        'token' => env('CV_API_TOKEN'),

        /*
         * Confidence minimum agar hasil CV
         * dianggap cukup yakin untuk otomatis.
         */
        'confidence_threshold' => (float) env(
            'CV_CONFIDENCE_THRESHOLD',
            0.60
        ),

        /*
         * Lama hasil CV menunggu konfirmasi
         * nasabah melalui kiosk.
         */
        'confirmation_ttl_minutes' => (int) env(
            'CV_CONFIRMATION_TTL_MINUTES',
            3
        ),

        /*
         * Counter default.
         */
        'counter_code' => env(
            'CV_COUNTER_CODE',
            'CS-01'
        ),
    ],


];
