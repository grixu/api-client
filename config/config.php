<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'base_url' => env('API_BASE_URL'),
    'client_key' => env('API_CLIENT_KEY'),
    'client_id' => env('API_CLIENT_ID'),
    'oauth' => env('API_OAUTH'),
    'logging' => env('API_ERROR_LOGGING', true),
    'log_channel' => env('API_ERROR_LOG_CHANNEL', 'api-client'),
];
