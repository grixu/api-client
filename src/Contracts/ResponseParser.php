<?php

namespace Grixu\ApiClient\Contracts;

use Illuminate\Support\Collection;

interface ResponseParser
{
    public function parse(Collection $inputData): Collection;
}
