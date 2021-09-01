<?php

return [
    'auth_types' => [
        \Grixu\ApiClient\Enums\AuthType::OAUTH2()->value => \Grixu\ApiClient\Auth\OAuthToken::class,
    ],

    'logging' => env('API_ERROR_LOGGING', false),
    'log_channel' => env('API_ERROR_LOG_CHANNEL', 'api-client'),
    'timeout' => env('API_TIMEOUT', 5),
];
