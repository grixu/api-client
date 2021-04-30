<?php

namespace Grixu\ApiClient\Tests\Helpers;

use Illuminate\Support\Carbon;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\DataTransferObject;

class ExampleDto extends DataTransferObject
{
    public string $first;
    public string $second;
    public string $third;

    #[CastWith(CarbonCaster::class)]
    public Carbon|null $date;

    #[CastWith(FakeEnumCaster::class)]
    public FakeEnum|null $enum;

    public int|null $id;
}
