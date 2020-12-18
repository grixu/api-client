<?php

namespace Grixu\ApiClient\Tests;

use Grixu\ApiClient\ApiClient as ApiClientClass;
use Grixu\ApiClient\ApiClientFacade as ApiClient;
use Grixu\ApiClient\ApiClientServiceProvider;
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

        ApiClient::shouldReceive('make')->once()->andReturnNull();

        ApiClient::make(config('api-client.base_url'));
    }
}
