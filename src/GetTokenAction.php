<?php

namespace Grixu\ApiClient;

use Grixu\ApiClient\Exceptions\TokenIssueException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GetTokenAction
{
    public function execute(): ?string
    {
        $token = Cache::get('api-client-token');

        if (!$token) {
            $tokenRequest = Http::withHeaders(
                [
                    'Accept' => 'application/json'
                ]
            )
                ->post(
                    config('api-client.oauth'),
                    [
                        'grant_type' => 'client_credentials',
                        'client_id' => config('api-client.client_id'),
                        'client_secret' => config('api-client.client_key'),
                        'scope' => '*',
                    ]
                );

            if (!$tokenRequest->successful()) {
                throw new TokenIssueException($tokenRequest->body());
            }

            $token = $tokenRequest->json('access_token');
            Cache::put('api-client-token', $token);
        }

        return $token;
    }
}
