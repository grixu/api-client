<?php

namespace Grixu\ApiClient\Contracts;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

interface ResponseParser
{
    public function parse(Collection $inputData): Collection;
    public function parseElement(array $input): DataTransferObject;
}
