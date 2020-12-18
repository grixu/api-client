<?php

namespace Grixu\ApiClient\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class AccessDeniedException extends Exception
{
    private string $responseBody;

    public function __construct($responseBody)
    {
        $this->responseBody = $responseBody;
        parent::__construct();
    }

    public function report()
    {
        if (config('api-client.logging') == true && !empty(config('api-client.log_channel'))) {
            Log::channel(config('api-client.log_channel'))
                ->critical('Brak dostępu do modułu. Odpowiedź z serwera: ' . $this->responseBody);
        }
    }
}
