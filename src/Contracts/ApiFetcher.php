<?php

namespace Grixu\ApiClient\Contracts;

use Closure;
use Illuminate\Support\Collection;

interface ApiFetcher
{
    public function fetch(?Closure $before = null);
    public function getDataCollection(): Collection;
    public function parse(string $dtoClass);
}
