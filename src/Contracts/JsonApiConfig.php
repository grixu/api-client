<?php

namespace Grixu\ApiClient\Contracts;

interface JsonApiConfig extends Config
{
    public function getFilters(): array;
    public function getIncludes(): array;
    public function getSorts(): array;
    public function getPaginationParam(): string;
}
