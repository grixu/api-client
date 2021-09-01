<?php

namespace Grixu\ApiClient\Builders;

use Illuminate\Support\Collection;

class CommaSeparatedValueUrlBuilder extends AbstractMultiParamUrlBuilder
{
    private string $param;
    private Collection $values;

    public function get(): string
    {
        if (empty($this->param) || empty($this->values)) {
            return '';
        }

        return $this->param.'='.$this->values->join(',');
    }

    public function add($param, ...$values): static
    {
        $this->checkInAllowedValues($values);

        $this->param = $param;
        $this->values = collect($values);

        return $this;
    }
}
