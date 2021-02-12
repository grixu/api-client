<?php

namespace Grixu\ApiClient\Auth;

class AuthData
{
    public function __construct(protected string $key, protected string $secret)
    {}

    public function getKey(): string
    {
        return $this->key;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }
}
