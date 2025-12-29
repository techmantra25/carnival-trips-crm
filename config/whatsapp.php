<?php

return [
    'domain' => env('WHATSAPP_API_DOMAIN'),
    'version' => env('api_version', 'v1.0'),
    'channel' => env('WHATSAPP_CHANNEL_NUMBER'),
    'api_key' => env('WHATSAPP_API_KEY'),

    'product' => env('WHATSAPP_PRODUCT', 'whatsapp'),
    'recipient_type' => env('WHATSAPP_RECIPIENT_TYPE', 'individual'),
    'default_language' => env('WHATSAPP_DEFAULT_TEMPLATE_LANGUAGE', 'en_US'),
    'mobile_prefix' => env('MOBILE_PREFIX', '91'),
];
