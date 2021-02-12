<?php

namespace Grixu\ApiClient\Contracts;

interface FetchedData
{
    public function getData(): array;
    public function isMoreToLoad(): bool;
}
