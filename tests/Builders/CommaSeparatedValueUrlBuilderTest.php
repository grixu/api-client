<?php

namespace Grixu\ApiClient\Tests\Builders;

use Grixu\ApiClient\Builders\CommaSeparatedValueUrlBuilder;
use Grixu\ApiClient\Exceptions\NotAllowedValueException;
use Orchestra\Testbench\TestCase;

class CommaSeparatedValueUrlBuilderTest extends TestCase
{
    protected CommaSeparatedValueUrlBuilder $obj;

    protected function setUp(): void
    {
        parent::setUp();

        $this->obj = new CommaSeparatedValueUrlBuilder(
            [
                'some',
                'relationship',
                'more'
            ]
        );
    }

    /** @test */
    public function it_set_single_value_and_returns_part_of_query_string()
    {
        $this->obj->add('include', 'relationship');

        $returnedValue = $this->obj->get();

        $this->basicAssertions($returnedValue);
        $this->assertStringContainsString('relationship', $returnedValue);
    }

    protected function basicAssertions($returnedValue, string $paramName = 'include')
    {
        $this->assertNotEmpty($returnedValue);
        $this->assertIsString($returnedValue);
        $this->assertStringContainsString($paramName . '=', $returnedValue);
    }

    /** @test */
    public function it_set_multiple_values_and_returns_part_of_query_string()
    {
        $this->obj->add('include', 'some', 'relationship', 'more');

        $returnedValue = $this->obj->get();

        $this->basicAssertions($returnedValue);
        $this->assertStringContainsString('some', $returnedValue);
        $this->assertStringContainsString('relationship', $returnedValue);
        $this->assertStringContainsString('more', $returnedValue);
        $this->assertStringContainsString('some,relationship,more', $returnedValue);
    }

    /** @test */
    public function it_returns_empty_string_when_nothing_set()
    {
        $returnedValue = $this->obj->get();

        $this->assertEmpty($returnedValue);
    }

    /** @test */
    public function method_add_returns_static()
    {
        $returnedValue = $this->obj->add('include', 'some');

        $this->assertEquals(CommaSeparatedValueUrlBuilder::class, $this->obj::class);
    }

    /** @test */
    public function it_rejects_unallowed_values()
    {
        try {
            $this->obj->add('include', 'test');
            $this->assertTrue(false);
        } catch (NotAllowedValueException) {
            $this->assertTrue(true);
        }
    }
}
