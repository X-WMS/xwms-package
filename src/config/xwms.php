<?php

return [
    "client_key" => env("XWMS_SHARED_SECRET"),
    "vps_ip_address" => "",
    "sign" => [
        "successMessage" => "Manage your emails, grow your reach, and earn crypto with MailFi.",
        "successUrl" => "/",
        "fallbackUrl" => "/"
    ],

    "locale" => [
        "locales" => ['nl', 'en', 'ar', 'bn', 'de', 'es', 'id', 'pt', 'ru', 'zh'],
        "default" => "en"
    ],

    "google" => [
        "MapsApiKey" => env('GOOGLE_MAPS_API_KEY', null)
    ]
];