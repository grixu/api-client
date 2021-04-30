<?php

namespace Grixu\ApiClient\Tests\Helpers;

use JetBrains\PhpStorm\Pure;

class FakeEnum
{
    public function __construct(public string $value)
    {
    }

    #[Pure]
    public static function make(mixed $value): static
    {
        return new static($value);
    }
}
