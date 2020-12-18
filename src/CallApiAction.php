<?php

namespace Grixu\ApiClient;

use Grixu\ApiClient\Exceptions\AccessDeniedException;
use Grixu\ApiClient\Exceptions\ApiCallException;
use Grixu\ApiClient\Exceptions\WrongConfigException;
use Illuminate\Support\Facades\Http;

class CallApiAction
{
    private GetTokenAction $getToken;

    public function __construct()
    {
        $this->getToken = new GetTokenAction();
    }

    public function execute(string $url)
    {
        if (empty(config('api-client.base_url'))
            || empty(config('api-client.client_key'))
            || empty(config('api-client.client_id'))) {
            throw new WrongConfigException();
        }

        $token = $this->getToken->execute();

        $client = Http::withToken($token)
            ->withHeaders(
                [
                    'Accept' => 'application/json'
                ]
            )
            ->get(config('api-client.base_url').$url);

        if ($client->successful()) {
            return $client->json();
        }

        if ($client->failed()) {
            if ($client->status() === 401) {
                $resetTokenAction = new ResetTokenAction();
                $resetTokenAction->execute();
                // Token refreshed, then try again
                return $this->execute($url);
            }

            if ($client->status() == 403) {
                throw new AccessDeniedException($client->body());
            }
        }

        throw new ApiCallException($url, $client->body());
    }
}
