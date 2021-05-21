<?php

namespace Grixu\ApiClient\Tests\Data;

use Grixu\ApiClient\Data\StraightKeyParser;
use Grixu\ApiClient\Tests\Helpers\ExampleDto;
use Illuminate\Support\Collection;
use Orchestra\Testbench\TestCase;

class StraightKeyParserTest extends TestCase
{
    protected StraightKeyParser $obj;

    protected function setUp(): void
    {
        parent::setUp();

        $this->obj = new StraightKeyParser(ExampleDto::class);
    }

    /** @test */
    public function it_parsing_collection_of_arrays_to_collection_of_dtos()
    {
        $inputData = collect(
            [
                [
                    'first' => 'first entry',
                    'second' => 'second entry',
                    'third' => 'third entry'
                ]
            ]
        );

        $this->basicAssertions($inputData);
    }

    protected function basicAssertions($inputData): Collection
    {
        $returnedData = $this->obj->parse($inputData);

        $this->assertEquals(Collection::class, $returnedData::class);
        $this->assertCount(1, $returnedData);

        return $returnedData;
    }

    /** @test */
    public function it_skips_every_not_array_data()
    {
        $inputData = collect(
            [
                [
                    'first' => 'first entry',
                    'second' => 'second entry',
                    'third' => 'third entry'
                ],
                'crap',
                666
            ]
        );

        $this->basicAssertions($inputData);
    }

    /** @test */
    public function it_replaces_datetime_to_carbon()
    {
        $date = now();
        $inputData = collect(
            [
                [
                    'first' => 'first entry',
                    'second' => 'second entry',
                    'third' => 'third entry',
                    'date' => $date->toISOString(),
                ],
            ]
        );


        $returnedData = $this->basicAssertions($inputData);

        $this->assertEquals($date->timestamp, $returnedData->first()->date->timestamp);
    }

    /** @test */
    public function it_do_not_replace_int_to_carbon()
    {
        $inputData = collect(
            [
                [
                    'first' => 'first entry',
                    'second' => 'second entry',
                    'third' => 'third entry',
                    'id' => 10987
                ],
            ]
        );

        $this->basicAssertions($inputData);
    }

    /** @test */
    public function it_do_not_replace_some_numeric_strings_to_carbon()
    {
        $inputData = collect(
            [
                [
                    'first' => 'first entry',
                    'second' => 'second entry',
                    'third' => '568845115895',
                ],
            ]
        );

        $this->basicAssertions($inputData);
    }

    /** @test */
    public function it_replaces_enums()
    {
        $inputData = collect(
            [
                [
                    'first' => 'first entry is very long it could literally over 27 characters',
                    'second' => 'second entry',
                    'third' => '568845115895',
                    'enum' => 'hello'
                ],
            ]
        );

        $this->basicAssertions($inputData);
    }

    /** @test */
    public function it_parsing_collection_of_arrays_with_relations_data_to_collection_of_dtos()
    {
        $inputData = collect(
            [
                [
                    'first' => 'first entry',
                    'second' => 'second entry',
                    'third' => 'third entry',
                    'relations' => [
                        [
                            'test' => 'another_thing'
                        ]
                    ]
                ]
            ]
        );

        $this->basicAssertions($inputData);
    }
}
