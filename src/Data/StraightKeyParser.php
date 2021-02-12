<?php

namespace Grixu\ApiClient\Data;

use Grixu\ApiClient\Contracts\ResponseParser;
use Illuminate\Support\Collection;

class StraightKeyParser implements ResponseParser
{
    public function __construct(private string $dtoClass)
    {}

    public function parse(Collection $inputData): Collection
    {
        $parsed = collect();

        foreach ($inputData as $data) {
            if (!is_array($data)) {
                continue;
            }

            $dto = new $this->dtoClass($data);

            $parsed->push($dto);
        }

        return $parsed;
    }
}
