<?php

namespace Grixu\ApiClient\Contracts;

use Grixu\ApiClient\Auth\AuthData;
use Grixu\ApiClient\Enums\AuthType;
use GuzzleHttp\Psr7\Uri;

interface Config
{
    public static function make(array $config): static;
    public function getBaseUrl(): Uri;
    public function getResponseDataClass(): string;
    public function getResponseParserClass(): string;
    public function getAuthUrl(): ?Uri;
    public function getAuthType(): ?AuthType;
    public function getAuthData(): ?AuthData;
}
