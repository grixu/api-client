<?php

namespace Grixu\ApiClient\Tests\Builders;

use Grixu\ApiClient\Builders\ArrayBasedUrlBuilder;
use Grixu\ApiClient\Exceptions\NotAllowedValueException;
use Orchestra\Testbench\TestCase;

class ArrayBasedUrlBuilderTest extends TestCase
{
    protected ArrayBasedUrlBuilder $obj;

    protected function setUp(): void
    {
        parent::setUp();

        $this->obj = new ArrayBasedUrlBuilder(
            [
                'first',
                'second',
            ]
        );
    }

    /** @test */
    public function it_adds_single_param_to_set()
    {
        $this->obj->add('first', 'value');

        $returnedValue = $this->obj->get();

        $this->basicAssertions($returnedValue);
        $this->assertStringContainsString('[first]=value', $returnedValue);
        $this->assertStringNotContainsString('&', $returnedValue);
    }

    protected function basicAssertions($returnedValue): void
    {
        $this->assertNotEmpty($returnedValue);
        $this->assertIsString($returnedValue);
    }

    /** @test */
    public function it_adds_multiple_params_to_set()
    {
        $this->obj->add('first', 'value_one', 'value_two');

        $returnedValue = $this->obj->get();

        $this->basicAssertions($returnedValue);
        $this->assertStringContainsString('[first]=value_one,value_two', $returnedValue);
        $this->assertStringNotContainsString('&', $returnedValue);
    }

    /** @test */
    public function it_handle_multiple_times_adding_single_or_multiple_params_to_set()
    {
        $this->obj->add('first', 'value_one');
        $this->obj->add('second', 'value_two');

        $returnedValue = $this->obj->get();

        $this->basicAssertions($returnedValue);
        $this->assertStringContainsString('[first]=value_one', $returnedValue);
        $this->assertStringContainsString('[second]=value_two', $returnedValue);
        $this->assertStringContainsString('&', $returnedValue);
    }

    /** @test */
    public function it_returns_empty_value_when_nothing_was_added()
    {
        $returnedValue = $this->obj->get();

        $this->assertEmpty($returnedValue);
    }

    /** @test */
    public function it_returns_static_on_add()
    {
        $returnedValue = $this->obj->add('first', 'some');

        $this->assertEquals(ArrayBasedUrlBuilder::class, $this->obj::class);
    }

    /** @test */
    public function it_rejects_unallowed_param_name()
    {
        try {
            $this->obj->add('some', 'some');
            $this->assertTrue(false);
        } catch (NotAllowedValueException) {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function it_return_empty_string_on_getter_when_set_is_empty()
    {
        $returnedData = $this->obj->get();

        $this->assertEmpty($returnedData);
    }
}
