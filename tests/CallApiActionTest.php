<?php

namespace Grixu\ApiClient\Tests;

use Grixu\ApiClient\CallApiAction;
use Grixu\ApiClient\Exceptions\AccessDeniedException;
use Grixu\ApiClient\Exceptions\ApiCallException;
use Grixu\ApiClient\Exceptions\TokenIssueException;
use Grixu\ApiClient\Exceptions\WrongConfigException;
use Grixu\ApiClient\ApiClientServiceProvider;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;

class CallApiActionTest extends TestCase
{
    private CallApiAction $action;
    private string $url;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new CallApiAction();
        $this->url = config('api-client.base_url');
    }

    protected function getPackageProviders($app)
    {
        return [ApiClientServiceProvider::class];
    }

    protected function useClearConfig($app)
    {
        $app->config->set('api-client.base_url', '');
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

        $result = $this->action->execute($this->url);

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
            $this->action->execute($this->url);
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
            $result = $this->action->execute($this->url);

            $this->assertIsArray($result);
            $this->assertArrayHasKey('data', $result);
        } catch (TokenIssueException $e) {
            $this->assertTrue(false);
        } catch (ApiCallException $e) {
            $this->assertTrue(false);
        }
    }

    /**
     * @test
     * @environment-setup useClearConfig
     */
    public function with_no_config()
    {
        try {
            $this->action->execute($this->url);
            $this->assertTrue(false);
        } catch (ApiCallException $e) {
            $this->assertTrue(false);
        } catch (TokenIssueException $e) {
            $this->assertTrue(false);
        } catch (WrongConfigException $e) {
            $this->assertTrue(true);
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
            $this->action->execute($this->url);
        } catch (ApiCallException $e) {
            $this->assertTrue(false);
        } catch (TokenIssueException $e) {
            $this->assertTrue(false);
        } catch (AccessDeniedException $e) {
            $this->assertTrue(true);
        }
    }
}
