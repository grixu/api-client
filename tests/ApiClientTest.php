<?php

namespace Grixu\ApiClient\Tests;

use Grixu\ApiClient\ApiClient;
use Grixu\ApiClient\ApiClientServiceProvider;
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

        $obj = new ApiClient();
        $result = $obj->make(config('api-client.base_url'));

        $this->assertNotEmpty($result);
    }
}
