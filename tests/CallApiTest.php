<?php

namespace Grixu\ApiClient\Tests;

use Grixu\ApiClient\CallApi;
use Grixu\ApiClient\Exceptions\AccessDeniedException;
use Grixu\ApiClient\Exceptions\ApiCallException;
use Grixu\ApiClient\Exceptions\TokenIssueException;
use Grixu\ApiClient\ApiClientServiceProvider;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;

class CallApiTest extends TestCase
{
    private CallApi $action;
    private string $url;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new CallApi(
            env('TEST_BASE_URL'),
            env('TEST_OAUTH'),
            env('TEST_CLIENT_ID'),
            env('TEST_CLIENT_KEY'),
            'test'
        );
        $this->url = env('TEST_BASE_URL');
    }

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

        $result = $this->action->call($this->url);

        $this->assertNotEmpty($result);
    }

    /** @test */
    public function with_http_error()
    {
        Http::fake(
            [
                '*' => Http::response('Not Found.', 404)
            ]
        );

        try {
            $this->action->call($this->url);
        } catch (ApiCallException $e) {
            $this->assertTrue(true);
        } catch (TokenIssueException $e) {
            $this->assertTrue(false);
        }
    }

    /** @test */
    public function with_token_revalidation()
    {
        Http::fake(
            [
                '*' => Http::sequence()
                    ->push('Unauthenticated.', 401)
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

        try {
            $result = $this->action->call($this->url);
            $this->assertIsArray($result);
            $this->assertArrayHasKey('data', $result);
        } catch (TokenIssueException $e) {
            $this->assertTrue(false);
        } catch (ApiCallException $e) {
            $this->assertTrue(false);
        }
    }

    /** @test */
    public function with_access_denied()
    {
        Http::fake(
            [
                '*' => Http::response('Access Denied', 403)
            ]
        );

        try {
            $this->action->call($this->url);
        } catch (ApiCallException $e) {
            $this->assertTrue(false);
        } catch (TokenIssueException $e) {
            $this->assertTrue(false);
        } catch (AccessDeniedException $e) {
            $this->assertTrue(true);
        }
    }
}
