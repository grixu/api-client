<?php

namespace Grixu\ApiClient\Tests;

use Grixu\ApiClient\Contracts\Config;
use Grixu\ApiClient\Tests\Helpers\FakeConfig;
use Grixu\ApiClient\UrlCompose;
use GuzzleHttp\Psr7\Uri;
use Orchestra\Testbench\TestCase;

class UrlComposeTest extends TestCase
{
    protected UrlCompose $obj;
    protected Config $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = FakeConfig::make(
            filters: ['one', 'two', 'three'],
            includes: ['first', 'second', 'third'],
            sorts: ['first', 'second']
        );

        $this->obj = new UrlCompose($this->config, '/api/test');
    }

    /** @test */
    public function it_constructs()
    {
        $this->assertEquals(UrlCompose::class, $this->obj::class);
    }

    /** @test */
    public function it_add_filters_and_return_self()
    {
        $returnedValue = $this->obj->addFilter('one', 'test');

        $this->assertEquals(UrlCompose::class, $returnedValue::class);
        $this->assertUri(str_replace('%3D', '=', rawurlencode('filter[one]=test')));
    }

    protected function assertUri(string $query): void
    {
        $returnedUri = $this->obj->get();

        $this->assertEquals(Uri::class, $returnedUri::class);
        $this->assertStringContainsString($query, $returnedUri->getQuery());
        $this->assertStringNotContainsString('&', $returnedUri->getQuery());
    }

    /** @test */
    public function it_add_includes_and_return_self()
    {
        $returnedValue = $this->obj->addInclude('includes', 'first');

        $this->assertEquals(UrlCompose::class, $returnedValue::class);
        $this->assertUri('includes=first');
    }

    /** @test */
    public function it_add_sort_and_return_self()
    {
        $returnedValue = $this->obj->addSort('sorts', 'first');

        $this->assertEquals(UrlCompose::class, $returnedValue::class);
        $this->assertUri('sorts=first');
    }

    /** @test */
    public function it_include_path_in_generated_uri()
    {
        $returnedValue = $this->obj->get();

        $this->assertStringContainsString('/api/test', $returnedValue);
    }

    /** @test */
    public function it_increase_to_next_page_even_if_this_param_was_not_set_earlier()
    {
        $this->assertEquals(UrlCompose::class, $this->obj->nextPage()::class);
        $this->assertStringContainsString('2', $this->obj->get());
    }
}
