<?php

namespace Grixu\ApiClient\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class ApiCallException extends Exception
{
    private string $url;
    private string $responseBody;

    public function __construct(string $url, $responseBody)
    {
        $this->url = $url;
        $this->responseBody = $responseBody;
        parent::__construct();
    }

    public function report()
    {
        if (config('api-client.logging') == true && !empty(config('api-client.log_channel'))) {
            Log::channel(config('api-client.log_channel'))->critical('Błąd zapytania do Socius API. URL: ' . $this->url);
            Log::channel(config('api-client.log_channel'))->critical('Odpowiedź z serwera ' . $this->responseBody);
        }
    }
}
