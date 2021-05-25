<?php

namespace Grixu\ApiClient\Tests\Data;

use Grixu\ApiClient\ApiClientServiceProvider;
use Grixu\ApiClient\ApiConfig;
use Grixu\ApiClient\Auth\OAuthToken;
use Grixu\ApiClient\Data\DataFetcher;
use Grixu\ApiClient\Data\PaginatedData;
use Grixu\ApiClient\Exceptions\AccessDeniedException;
use Grixu\ApiClient\Exceptions\ApiCallException;
use Grixu\ApiClient\Exceptions\MissingResponseClassNameInConfigException;
use Grixu\ApiClient\Tests\Helpers\FakeConfig;
use Grixu\ApiClient\Tests\Helpers\HttpMocksTrait;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;

class DataFetcherTest extends TestCase
{
    use HttpMocksTrait;

    protected DataFetcher $obj;

    protected function setUp(): void
    {
        parent::setUp();

        $config = FakeConfig::make();
        $token = new OAuthToken($config);
        Cache::shouldReceive('get')->once()->andReturn('cached_token');
        $token->getToken();

        $uri = new Uri('http://rywal.com.pl');

        $this->obj = new DataFetcher($uri, $config->getResponseDataClass(), $token);
    }

    protected function getPackageProviders($app)
    {
        return [
            ApiClientServiceProvider::class
        ];
    }

    /** @test */
    public function it_makes_http_call()
    {
        $this->mockHttpSuccessfulResponse();

        $this->basicAssertions();
    }

    protected function basicAssertions()
    {
        $returnedValue = $this->obj->get();

        $this->assertEquals(PaginatedData::class, $returnedValue::class);
        $this->assertNotEmpty($returnedValue->getData());
        $this->assertNotEmpty($returnedValue->getCurrentPage());
        $this->assertNotEmpty($returnedValue->getPerPage());
        $this->assertNotEmpty($returnedValue->getTotalPages());
    }

    /** @test */
    public function it_takes_and_use_custom_response_class_name()
    {
        $this->mockHttpSuccessfulResponse();

        $this->obj->fetch(PaginatedData::class);

        $this->basicAssertions();
    }

    /** @test */
    public function it_make_reauthorization_when_used_token_auth_is_outdated(): void
    {
        $this->mockHttpSequenceWith401();
        $this->mockCache();

        $this->basicAssertions();
    }

    protected function mockCache(): void
    {
        Cache::shouldReceive('forget')->once()->andReturnNull();
        Cache::shouldReceive('put')->once()->andReturnNull();
    }

    /** @test */
    public function it_handles_forbidden_access_response()
    {
        $this->mockHttp403Response();

        try {
            $this->obj->fetch();
            $this->assertTrue(false);
        } catch (AccessDeniedException) {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function it_handles_other_http_errors()
    {
        $this->mockHttp404Response();

        try {
            $this->obj->fetch();
            $this->assertTrue(false);
        } catch (ApiCallException) {
            $this->assertTrue(true);
        }
    }
}
