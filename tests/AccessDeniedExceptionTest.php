<?php

namespace Grixu\ApiClient\Tests;

use Grixu\ApiClient\ApiClientServiceProvider;
use Grixu\ApiClient\Exceptions\AccessDeniedException;
use Illuminate\Support\Facades\Log;
use Orchestra\Testbench\TestCase;

class AccessDeniedExceptionTest extends TestCase
{
    protected AccessDeniedException $obj;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new AccessDeniedException('response');
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

        $this->obj->report();
    }
}
