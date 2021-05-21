<?php

namespace Grixu\ApiClient\Contracts;

use Illuminate\Support\Collection;

interface FetchedData
{
    public function getData(): array;
    public function getDataCollection(): Collection;
    public function isMoreToLoad(): bool;
}
