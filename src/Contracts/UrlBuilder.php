<?php

namespace Grixu\ApiClient\Contracts;

interface UrlBuilder
{
    public function get(): string;
    public function add(string $param, ...$values): static;
}
