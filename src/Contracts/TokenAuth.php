<?php

namespace Grixu\ApiClient\Contracts;

interface TokenAuth
{
    public function getToken(): string;
    public function authorize(): void;
    public function reset(): void;
}
