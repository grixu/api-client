<?php

namespace Grixu\ApiClient\Auth;

use Grixu\ApiClient\Contracts\Config;
use Grixu\ApiClient\Contracts\TokenAuth;
use Grixu\ApiClient\Enums\AuthType;
use Grixu\ApiClient\Exceptions\InvalidAuthTypeException;
use Grixu\ApiClient\Exceptions\TokenIssueException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OAuthToken implements TokenAuth
{
    private Config $config;
    private ?string $token;
    private string $cacheName;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->validateAuthType();
        $this->generateCacheName();
    }

    protected function validateAuthType()
    {
        if ($this->config->getAuthType() != AuthType::OAUTH2()) {
            throw new InvalidAuthTypeException();
        }
    }

    private function generateCacheName(): void
    {
        $this->cacheName = md5($this->config->getAuthUrl() . $this->config->getAuthType()->value);
    }

    public function getToken(): string
    {
        if (empty($this->token)) {
            $this->authorize();
        }

        return $this->token;
    }

    public function authorize(): void
    {
        try {
            $this->getTokenFromCache();
        } catch (\Exception) {
            $this->getTokenFromAuthServer();
        }
    }

    private function getTokenFromCache(): void
    {
        $this->token = Cache::get($this->cacheName);

        if (empty($this->token)) {
            throw new \Exception('Empty cache');
        }
    }

    private function getTokenFromAuthServer(): void
    {
        $tokenResponse = $this->makeAuthRequest();
        $this->validateAuthResponse($tokenResponse);

        $this->token = $tokenResponse->json('access_token');
        Cache::put($this->cacheName, $this->token);
    }

    private function makeAuthRequest(): Response
    {
        return Http::withHeaders(
            [
                'Accept' => 'application/json'
            ]
        )
            ->post(
                $this->config->getAuthUrl(),
                [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->config->getAuthData()->getKey(),
                    'client_secret' => $this->config->getAuthData()->getSecret(),
                    'scope' => '*',
                ]
            );
    }

    private function validateAuthResponse(Response $authResponse): void
    {
        if (!$authResponse->successful()) {
            throw new TokenIssueException($authResponse->body());
        }
    }

    public function reset(): void
    {
        Cache::forget($this->cacheName);
        $this->getTokenFromAuthServer();
    }
}
