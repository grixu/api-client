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

        $returnedData = $this->obj->parse($inputData);

        $this->assertEquals(Collection::class, $returnedData::class);
        $this->assertCount(1, $returnedData);
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

        $returnedData = $this->obj->parse($inputData);

        $this->assertEquals(Collection::class, $returnedData::class);
        $this->assertCount(1, $returnedData);
    }
}
