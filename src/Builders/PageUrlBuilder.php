<?php

namespace Grixu\ApiClient\Builders;

use Grixu\ApiClient\Contracts\UrlBuilder;

class PageUrlBuilder implements UrlBuilder
{
    private string $param;
    private int $value;

    public function get(): string
    {
        if (empty($this->param) || empty($this->value)) {
            return '';
        }

        return $this->param.'='.$this->value;
    }

    public function add(string $param, ...$values): static
    {
        $this->validateValue($values[0]);

        $this->param = $param;
        $this->value = $values[0];

        return $this;
    }

    protected function validateValue(mixed $value): void
    {
        if (!is_int($value)) {
            throw new \TypeError('Wartość nie jest liczbą');
        }
    }

    public function next(): static
    {
        $this->value += 1;

        return $this;
    }
}
