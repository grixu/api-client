<?php

namespace Grixu\ApiClient\Config;

use Grixu\ApiClient\Auth\AuthData;
use Grixu\ApiClient\Contracts\Config;
use Grixu\ApiClient\Enums\AuthType;
use GuzzleHttp\Psr7\Uri;

abstract class BaseConfig implements Config
{
    protected Uri $baseUrl;
    protected string $responseDataClass;
    protected string $responseParserClass;
    protected ?Uri $authUrl = null;
    protected ?AuthType $authType = null;
    protected ?AuthData $authData = null;

    public static function make(array $config): static
    {
        return new static(...$config);
    }

    public function __construct(
        string $baseUrl,
        string $responseDataClass,
        string $responseParserClass,
        AuthType|string|null $authType = null,
        ?string $authUrl = null,
        AuthData|array|null $authData = null,
    ) {
        $this->baseUrl = new Uri($baseUrl);
        $this->responseDataClass = $responseDataClass;
        $this->responseParserClass = $responseParserClass;

        if (!empty($authType)) {
            $this->parseAuthType($authType);
            $this->authUrl = new Uri($authUrl);
            $this->parseAuthData($authData);
        }
    }

    private function parseAuthType(AuthType|string $authType): void
    {
        if ($authType instanceof AuthType) {
            $this->authType = $authType;
        } else {
            $this->authType = new AuthType(strtoupper($authType));
        }
    }

    private function parseAuthData(AuthData|array $authData): void
    {
        if ($authData instanceof AuthData) {
            $this->authData = $authData;
        } else {
            $this->authData = new AuthData(...$authData);
        }
    }

    public function getBaseUrl(): Uri
    {
        return $this->baseUrl;
    }

    public function getAuthUrl(): ?Uri
    {
        return $this->authUrl;
    }

    public function getAuthType(): ?AuthType
    {
        return $this->authType;
    }

    public function getAuthData(): ?AuthData
    {
        return $this->authData;
    }

    public function getResponseDataClass(): string
    {
        return $this->responseDataClass;
    }

    public function getResponseParserClass(): string
    {
        return $this->responseParserClass;
    }
}
