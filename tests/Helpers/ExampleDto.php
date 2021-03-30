<?php

namespace Grixu\ApiClient\Tests\Helpers;

use Illuminate\Support\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class ExampleDto extends DataTransferObject
{
    public string $first;
    public string $second;
    public string $third;
    public ?Carbon $date;
    public ?FakeEnum $enum;
    public ?int $id;
}
