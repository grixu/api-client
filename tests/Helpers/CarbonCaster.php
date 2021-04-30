<?php

namespace Grixu\ApiClient\Tests\Helpers;

use Illuminate\Support\Carbon;
use Spatie\DataTransferObject\Caster;

class CarbonCaster implements Caster
{
    public function cast(mixed $value): Carbon
    {
        return Carbon::createFromTimeString($value);
    }
}
