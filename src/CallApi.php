<?php

namespace Grixu\ApiClient;

use Grixu\ApiClient\Exceptions\AccessDeniedException;
use Grixu\ApiClient\Exceptions\ApiCallException;
use Illuminate\Support\Facades\Http;

class CallApi
{
    protected GetToken $getToken;
    protected string $baseUrl;

    public function __construct(string $baseUrl, string $oAuthUrl, string $clientId, string $clientKey, string $cacheKey)
    {
        $this->baseUrl = $baseUrl;
        $this->getToken = new GetToken($oAuthUrl, $clientId, $clientKey, $cacheKey);
    }

    public function call(string $url): array
    {
        $token = $this->getToken->get();

        $client = Http::withToken($token)
            ->withHeaders(
                [
                    'Accept' => 'application/json'
                ]
            )
            ->get($this->baseUrl.$url);

        if ($client->successful()) {
            return $client->json();
        }

        if ($client->failed()) {
            if ($client->status() === 401) {
                $this->getToken->reset();
                // Token refreshed, then try again
                return $this->call($url);
            }

            if ($client->status() == 403) {
                throw new AccessDeniedException($client->body());
            }
        }

        throw new ApiCallException($url, $client->body());
    }
}
