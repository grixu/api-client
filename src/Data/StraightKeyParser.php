<?php

namespace Grixu\ApiClient\Data;

use ErrorException;
use Grixu\ApiClient\Contracts\ResponseParser;
use Illuminate\Support\Carbon;
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
            /** @var ReflectionProperty $field */
            $type = $field->getType()->getName();
            $dtoFieldName = $field->getName();
            $arrayFieldName = Str::camel($dtoFieldName);

            try {
                if (str_contains($type, 'Enum')) {
                    $data[$dtoFieldName] = new $type($input[$arrayFieldName]);
                } elseif ($type === 'Illuminate\Support\Carbon') {
                    $data[$dtoFieldName] = Carbon::createFromTimeString($input[$arrayFieldName]);
                } else {
                    $data[$dtoFieldName] = $input[$arrayFieldName];
                }
            } catch (ErrorException) {
                $data[$dtoFieldName] = null;
            }
        }

        return $data;
    }
}
