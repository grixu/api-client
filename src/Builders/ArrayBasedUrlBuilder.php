<?php

namespace Grixu\ApiClient\Builders;

use Illuminate\Support\Collection;

class ArrayBasedUrlBuilder extends AbstractMultiParamUrlBuilder
{
    private Collection $set;
    private string $prefix;

    public function __construct(array $allowedValues, string $prefix = 'filter')
    {
        parent::__construct($allowedValues);
        $this->set = collect();
        $this->prefix = $prefix;
    }

    public function get(): string
    {
        return $this->set->join('&');
    }

    public function add($param, ...$values): static
    {
        $this->checkInAllowedValues([$param]);

        $valuesCollection = collect($values);
        $compiledString = $this->prefix.'['.$param.']='.$valuesCollection->join(',');
        $this->set->push($compiledString);

        return $this;
    }
}
