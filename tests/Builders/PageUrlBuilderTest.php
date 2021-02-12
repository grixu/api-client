<?php

namespace Grixu\ApiClient\Tests\Builders;

use Grixu\ApiClient\Builders\PageUrlBuilder;
use Orchestra\Testbench\TestCase;

class PageUrlBuilderTest extends TestCase
{
    protected PageUrlBuilder $obj;

    protected function setUp(): void
    {
        parent::setUp();

        $this->obj = new PageUrlBuilder();
    }

    /** @test */
    public function it_set_params_and_return_url_query_part()
    {
        $this->obj->add('page', 3);

        $returnedValue = $this->obj->get();

        $this->assertNotEmpty($returnedValue);
        $this->assertIsString($returnedValue);
    }

    /** @test */
    public function it_handle_even_when_multiple_params_will_be_passed_to_add_method()
    {
        $this->obj->add('page', 3, 4, 5);

        $returnedValue = $this->obj->get();

        $this->assertNotEmpty($returnedValue);
        $this->assertIsString($returnedValue);
        $this->assertStringContainsString('page', $returnedValue);
        $this->assertStringContainsString('3', $returnedValue);
        $this->assertStringNotContainsString('4', $returnedValue);
        $this->assertStringNotContainsString('5', $returnedValue);
    }

    /** @test */
    public function add_method_return_static()
    {
        $returnedValue = $this->obj->add('page', 1);

        $this->assertEquals(PageUrlBuilder::class, $this->obj::class);
    }

    /** @test */
    public function it_return_empty_string_when_nothing_was_set()
    {
        $returnedValue = $this->obj->get();

        $this->assertEmpty($returnedValue);
    }

    /** @test */
    public function it_checking_params_are_int()
    {
        try {
            $this->obj->add('page', 'a');
            $this->assertTrue(false);
        } catch (\TypeError) {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function next_method_increments_value()
    {
        $this->obj->add('page', 1);
        $this->obj->next();

        $returnedValue = $this->obj->get();

        $this->assertStringContainsString('page=2', $returnedValue);
    }
}
