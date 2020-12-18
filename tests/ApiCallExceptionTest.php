<?php

namespace Grixu\ApiClient\Tests;

use Grixu\ApiClient\ApiClientServiceProvider;
use Grixu\ApiClient\Exceptions\ApiCallException;
use Illuminate\Support\Facades\Log;
use Orchestra\Testbench\TestCase;

class ApiCallExceptionTest extends TestCase
{
    protected ApiCallException $obj;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new ApiCallException(config('api-client.base_url'), 'response');
    }

    protected function getPackageProviders($app)
    {
        return [ApiClientServiceProvider::class];
    }

    /** @test */
    public function check_report()
    {
        Log::shouldReceive('channel')->once()->with(config('api-client.log_channel'))->andReturnSelf();
        Log::shouldReceive('critical')->once()->andReturnNull();
        Log::shouldReceive('channel')->once()->with(config('api-client.log_channel'))->andReturnSelf();
        Log::shouldReceive('critical')->once()->andReturnNull();

        $this->obj->report();
    }
}
