<?php

namespace Grixu\ApiClient\Tests\Config;

use Grixu\ApiClient\Auth\AuthData;
use Grixu\ApiClient\Config\JsonApiConfig;
use Grixu\ApiClient\Enums\AuthType;
use Grixu\ApiClient\Tests\Helpers\FakeConfig;
use Orchestra\Testbench\TestCase;

class JsonApiConfigTest extends TestCase
{
    /** @test */
    public function construct_using_basic_types_without_filters_includes_sorts()
    {
        $obj = FakeConfig::make();

        $this->basicAssertions($obj);
        $this->assertFiltersIncludesSortsAreEmpty($obj);
    }

    protected function basicAssertions($obj)
    {
        $this->assertEquals(JsonApiConfig::class, $obj::class);
        $this->assertNotEmpty($obj->getBaseUrl());
        $this->assertNotEmpty($obj->getAuthUrl());
        $this->assertNotEmpty($obj->getAuthType());
        $this->assertNotEmpty($obj->getAuthData());
    }

    protected function assertFiltersIncludesSortsAreEmpty($obj): void
    {
        $this->assertEmpty($obj->getFilters());
        $this->assertEmpty($obj->getIncludes());
        $this->assertEmpty($obj->getSorts());
    }

    /** @test */
    public function it_constructs_using_class_based_types()
    {
        $obj = FakeConfig::make(
            authType: AuthType::OAUTH2(),
            authData: new AuthData('key', 'secret')
        );

        $this->basicAssertions($obj);
        $this->assertFiltersIncludesSortsAreEmpty($obj);
    }

    /** @test */
    public function it_constructs_with_filters()
    {
        $obj = FakeConfig::make(
            authType: AuthType::OAUTH2(),
            authData: new AuthData('key', 'secret'),
            filters: ['one', 'two']
        );

        $this->basicAssertions($obj);
        $this->assertNotEmpty($obj->getFilters());
    }

    /** @test */
    public function it_constructs_with_sorts()
    {
        $obj = FakeConfig::make(
            authType: AuthType::OAUTH2(),
            authData: new AuthData('key', 'secret'),
            sorts: ['one', 'two']
        );

        $this->basicAssertions($obj);
        $this->assertNotEmpty($obj->getSorts());
    }

    /** @test */
    public function it_construct_with_includes()
    {
        $obj = FakeConfig::make(
            authType: AuthType::OAUTH2(),
            authData: new AuthData('key', 'secret'),
            includes: ['one', 'two']
        );

        $this->basicAssertions($obj);
        $this->assertNotEmpty($obj->getIncludes());
    }
}
