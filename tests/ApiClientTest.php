<?php

namespace Grixu\ApiClient\Tests;

use Grixu\ApiClient\ApiClient;
use Grixu\ApiClient\ApiClientServiceProvider;
use Grixu\ApiClient\Exceptions\WrongConfigException;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;

class ApiClientTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ApiClientServiceProvider::class];
    }

    /** @test */
    public function normal_pass()
    {
        $this->createHttpFake();
        $obj = $this->createTestObj();
        $result = $obj->call(env('TEST_BASE_URL'));

        $this->assertNotEmpty($result);
    }

    protected function createTestObj()
    {
        return ApiClient::make(
            env('TEST_BASE_URL'),
            env('TEST_OAUTH'),
            env('TEST_CLIENT_ID'),
            env('TEST_CLIENT_KEY'),
            'test'
        );
    }

    protected function createHttpFake()
    {
        Http::fake(
            [
                '*' => Http::sequence()
                    ->push(
                        [
                            'access_token' => 'blebleble'
                        ],
                        200
                    )
                    ->push(
                        [
                            'data' => 'Yeees'
                        ],
                        200
                    )

            ]
        );
    }

    /**
     * @test
     * @environment-setup apiConfig
     */
    public function with_config_name()
    {
        $this->createHttpFake();

        $obj = ApiClient::make('project.api');
        $result = $obj->call(env('TEST_BASE_URL'));

        $this->assertNotEmpty($result);
    }

    protected function apiConfig($app)
    {
        $app->config->set(
            'project',
            [
                'api' => [
                    'baseUrl',
                    'oAuthUrl',
                    'id',
                    'key',
                    'cache'
                ]
            ]
        );
    }

    /** @test */
    public function wrong_options_passed()
    {
        try {
            ApiClient::make('lol', 'lol');

            $this->assertTrue(false);
        } catch (WrongConfigException $e) {
            $this->assertTrue(true);
        }
    }
}
