<?php

namespace Grixu\ApiClient\Data;

use Grixu\ApiClient\Contracts\ResponseParser;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionProperty;

class StraightKeyParser implements ResponseParser
{
    private $fields;

    public function __construct(private string $dtoClass)
    {
        $reflection = new ReflectionClass($this->dtoClass);
        $this->fields = collect($reflection->getProperties(ReflectionProperty::IS_PUBLIC));
    }

    public function parse(Collection $inputCollection): Collection
    {
        $parsed = collect();

        foreach ($inputCollection as $inputData) {
            if (!is_array($inputData)) {
                continue;
            }

            $preparedData = $this->parseElement($inputData);
            $dto = new $this->dtoClass($preparedData);

            $parsed->push($dto);
        }

        return $parsed;
    }

    protected function parseElement(array $input): array
    {
        $data = [];

        foreach ($this->fields as $field) {
            $dtoFieldName = $field->getName();
            $arrayFieldName = Str::snake($dtoFieldName);

            if ($dtoFieldName === 'relations' && isset($input[$arrayFieldName])) {
                $data[$dtoFieldName] = $this->parseRelationship($input[$arrayFieldName]);
            } else {
                $data[$dtoFieldName] = $input[$arrayFieldName] ?? null;
            }
        }

        return $data;
    }

    protected function parseRelationship(array $inputRelationships): array
    {
        $relationships = [];

        foreach ($inputRelationships as $input) {
            $relationship = [];
            foreach ($input as $key => $value) {
                $relationship[Str::camel($key)] = $value;
            }
            $relationships[] = $relationship;
        }

        return $relationships;
    }
}
