<?php

namespace Grixu\ApiClient\Tests;

use Grixu\ApiClient\ResetTokenAction;
use Grixu\ApiClient\ApiClientServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;

class ResetTokenActionTest extends TestCase
{
    private ResetTokenAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new ResetTokenAction();
    }

    protected function getPackageProviders($app): array
    {
        return [ApiClientServiceProvider::class];
    }

    /** @test */
    public function normal_pass(): void
    {
        Cache::shouldReceive('forget')->once()->andReturnNull();
        Cache::shouldReceive('get')->once()->andReturnNull();
        Cache::shouldReceive('put')->once()->andReturnNull();
        Http::fake(
            [
                '*' => Http::response(
                    [
                        'access_token' => 'blebleble'
                    ],
                    200
                )
            ]
        );

        $result = $this->action->execute();

        $this->assertNotEmpty($result);
        $this->assertIsString($result);
    }
}
