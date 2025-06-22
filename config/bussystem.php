<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | BusSystem API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for BusSystem transportation services integration.
    | You can get these credentials by registering at https://website.bussystem.eu/
    |
    */

    'api_url' => env('BUSSYSTEM_API_URL', 'https://test-api.bussystem.eu/server'),

    'login' => env('BUSSYSTEM_LOGIN'),

    'password' => env('BUSSYSTEM_PASSWORD'),

    'partner_id' => env('BUSSYSTEM_PARTNER_ID'),

    /*
    |--------------------------------------------------------------------------
    | Request Configuration
    |--------------------------------------------------------------------------
    */

    'timeout' => env('BUSSYSTEM_TIMEOUT', 120),

    'retry_attempts' => env('BUSSYSTEM_RETRY_ATTEMPTS', 3),

    'retry_delay' => env('BUSSYSTEM_RETRY_DELAY', 1000), // milliseconds

    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    */

    'default_currency' => env('BUSSYSTEM_DEFAULT_CURRENCY', 'EUR'),

    'default_language' => env('BUSSYSTEM_DEFAULT_LANGUAGE', 'en'),

    'default_api_version' => env('BUSSYSTEM_DEFAULT_API_VERSION', '1.1'),

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */

    'cache' => [
        'enabled' => env('BUSSYSTEM_CACHE_ENABLED', true),
        'prefix' => env('BUSSYSTEM_CACHE_PREFIX', 'bussystem'),
        'ttl' => [
            'points' => env('BUSSYSTEM_CACHE_POINTS_TTL', 3600), // 1 hour
            'routes' => env('BUSSYSTEM_CACHE_ROUTES_TTL', 300),  // 5 minutes
            'plans' => env('BUSSYSTEM_CACHE_PLANS_TTL', 86400),  // 24 hours
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    */

    'logging' => [
        'enabled' => env('BUSSYSTEM_LOGGING_ENABLED', true),
        'channel' => env('BUSSYSTEM_LOG_CHANNEL', 'daily'),
        'level' => env('BUSSYSTEM_LOG_LEVEL', 'info'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Response Format
    |--------------------------------------------------------------------------
    */

    'response_format' => env('BUSSYSTEM_RESPONSE_FORMAT', 'json'), // json or xml
];