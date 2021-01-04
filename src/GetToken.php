<?php

namespace Grixu\ApiClient;

use Grixu\ApiClient\Exceptions\TokenIssueException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GetToken
{
    protected string $oAuthUrl;
    protected string $clientKey;
    protected string $clientId;
    protected string $cacheKey;

    public function __construct(string $oAuthUrl, string $clientId, string $clientKey, string $cacheKey)
    {
        $this->oAuthUrl = $oAuthUrl;
        $this->clientId = $clientId;
        $this->clientKey = $clientKey;
        $this->cacheKey = $cacheKey;
    }

    public function get(): ?string
    {
        $token = Cache::get($this->cacheKey);

        if (!$token) {
            $tokenRequest = Http::withHeaders(
                [
                    'Accept' => 'application/json'
                ]
            )
                ->post(
                    $this->oAuthUrl,
                    [
                        'grant_type' => 'client_credentials',
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientKey,
                        'scope' => '*',
                    ]
                );

            if (!$tokenRequest->successful()) {
                throw new TokenIssueException($tokenRequest->body());
            }

            $token = $tokenRequest->json('access_token');
            Cache::put($this->cacheKey, $token);
        }

        return $token;
    }

    public function reset(): ?string
    {
        Cache::forget($this->cacheKey);
        return $this->get();
    }
}
