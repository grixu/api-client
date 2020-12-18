<?php

namespace Grixu\ApiClient\Tests;

use Grixu\ApiClient\GetTokenAction;
use Grixu\ApiClient\Exceptions\TokenIssueException;
use Grixu\ApiClient\ApiClientServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;

class GetTokenActionTest extends TestCase
{
    private GetTokenAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new GetTokenAction();
    }

    protected function getPackageProviders($app)
    {
        return [ApiClientServiceProvider::class];
    }

    /** @test */
    public function normal_pass()
    {
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

    /** @test */
    public function with_http_error()
    {
        Cache::shouldReceive('get')->once()->andReturnNull();
        Http::fake(
            [
                '*' => Http::response('Not Found.', 404)
            ]
        );

        try {
            $this->action->execute();
        } catch (TokenIssueException $e) {
            $this->assertTrue(true);
        }
    }
}
