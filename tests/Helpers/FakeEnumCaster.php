<?php

namespace Grixu\ApiClient\Tests\Helpers;

use Spatie\DataTransferObject\Caster;

class FakeEnumCaster implements Caster
{
    public function cast(mixed $value): FakeEnum
    {
        if ($value instanceof FakeEnum) return $value;

        return FakeEnum::make($value);
    }
}
