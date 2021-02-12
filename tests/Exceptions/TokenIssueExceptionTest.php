<?php

namespace Grixu\ApiClient\Tests\Exceptions;

use Grixu\ApiClient\ApiClientServiceProvider;
use Grixu\ApiClient\Exceptions\TokenIssueException;
use Illuminate\Support\Facades\Log;
use Orchestra\Testbench\TestCase;

class TokenIssueExceptionTest extends TestCase
{
    protected TokenIssueException $obj;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new TokenIssueException('response');
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
