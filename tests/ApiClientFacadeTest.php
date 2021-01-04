<?php

namespace Grixu\ApiClient\Tests;

use Grixu\ApiClient\ApiClient as ApiClientClass;
use Grixu\ApiClient\ApiClientFacade as ApiClient;
use Grixu\ApiClient\ApiClientServiceProvider;
use Grixu\ApiClient\CallApi;
use Orchestra\Testbench\TestCase;

class ApiClientFacadeTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ApiClientServiceProvider::class];
    }

    /** @test */
    public function check_return_value()
    {
        $obj = $this->app->factory('api-client');
        $this->assertEquals(ApiClientClass::class, get_class($obj()));

        ApiClient::shouldReceive('make')->once()->andReturn(
            new CallApi(
                env('TEST_BASE_URL'),
                env('TEST_OAUTH'),
                env('TEST_CLIENT_ID'),
                env('TEST_CLIENT_KEY'),
                'test'
            )
        );

        ApiClient::make(env('TEST_BASE_URL'));
    }
}
