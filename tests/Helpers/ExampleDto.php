<?php

namespace Grixu\ApiClient\Tests\Helpers;

use Spatie\DataTransferObject\DataTransferObject;

class ExampleDto extends DataTransferObject
{
    public string $first;
    public string $second;
    public string $third;
}
