<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'logging' => env('API_ERROR_LOGGING', false),
    'log_channel' => env('API_ERROR_LOG_CHANNEL', 'api-client'),
];
