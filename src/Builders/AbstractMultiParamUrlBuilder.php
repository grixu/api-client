<?php

namespace Grixu\ApiClient\Builders;

use Grixu\ApiClient\Contracts\UrlBuilder;
use Grixu\ApiClient\Exceptions\NotAllowedValueException;

abstract class AbstractMultiParamUrlBuilder implements UrlBuilder
{
    private array $allowedValues;

    public function __construct(array $allowedValues)
    {
        $this->allowedValues = $allowedValues;
    }

    protected function checkInConfig(array $params): void
    {
        foreach ($params as $param) {
            if (!in_array($param, $this->allowedValues)) {
                throw new NotAllowedValueException();
            }
        }
    }
}
