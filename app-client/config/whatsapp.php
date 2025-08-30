<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Cloud API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for WhatsApp Cloud API integration.
    | The settings are used by the WhatsApp service to send messages.
    |
    */

    'api' => [
        'base_url' => env('WHATSAPP_API_BASE_URL', 'https://graph.facebook.com/v22.0'),
        'timeout' => env('WHATSAPP_API_TIMEOUT', 30),
        'connect_timeout' => env('WHATSAPP_API_CONNECT_TIMEOUT', 10),
    ],

    'defaults' => [
        'language' => env('WHATSAPP_DEFAULT_LANGUAGE', 'pt_BR'),
        'messaging_product' => 'whatsapp',
    ],

    'templates' => [
        'hello_world' => [
            'name' => 'hello_world',
            'language' => [
                'code' => 'pt_BR'
            ]
        ],
    ],

    'logging' => [
        'enabled' => env('WHATSAPP_LOGGING_ENABLED', true),
        'channel' => env('WHATSAPP_LOG_CHANNEL', 'whatsapp'),
    ],
];
