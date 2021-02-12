<?php

namespace Grixu\ApiClient\Tests\Auth;

use Grixu\ApiClient\Auth\OAuthToken;
use Grixu\ApiClient\Contracts\Config;
use Grixu\ApiClient\Exceptions\InvalidAuthTypeException;
use Grixu\ApiClient\Exceptions\TokenIssueException;
use Grixu\ApiClient\Tests\Helpers\FakeConfig;
use Grixu\ApiClient\Tests\Helpers\HttpMocksTrait;
use Illuminate\Support\Facades\Cache;
use Orchestra\Testbench\TestCase;

class OAuthTokenTest extends TestCase
{
    use HttpMocksTrait;

    protected OAuthToken $obj;
    protected Config $config;

    /** @test */
    public function it_constructs()
    {
        $this->buildConfigAndObj();
        $this->assertEquals(OAuthToken::class, $this->obj::class);
    }

    protected function buildConfigAndObj()
    {
        $this->config = FakeConfig::make();
        $this->obj = new OAuthToken($this->config);
    }

    /** @test */
    public function get_token_on_empty_token_value_call_for_auth()
    {
        $this->mockCache();
        $this->mockHttpAuthResponse();
        $this->buildConfigAndObj();

        $this->obj->getToken();
    }

    protected function mockCache(): void
    {
        Cache::shouldReceive('get')->once()->andReturnNull();
        Cache::shouldReceive('put')->once()->andReturnNull();
    }

    /** @test */
    public function authorize_check_cache_first_and_hit()
    {
        Cache::shouldReceive('get')->once()->andReturn('cached_token');
        $this->buildConfigAndObj();

        $this->assertToken('cached_token');
    }

    /** @test */
    public function authorize_check_cache_first_and_miss()
    {
        $this->mockCache();
        $this->mockHttpAuthResponse();
        $this->buildConfigAndObj();

        $this->obj->authorize();

        $this->assertToken('blebleble');
    }

    protected function assertToken(string $expectedToken)
    {
        $token = $this->obj->getToken();
        $this->assertNotEmpty($token);
        $this->assertIsString($token);
        $this->assertEquals($expectedToken, $token);
    }

    /** @test */
    public function reset_clear_cache_and_make_auth_call()
    {
        Cache::shouldReceive('forget')->once()->andReturnNull();
        Cache::shouldReceive('put')->once()->andReturnNull();
        $this->mockHttpAuthResponse();
        $this->buildConfigAndObj();

        $this->obj->reset();

        $this->assertToken('blebleble');
    }

    /** @test */
    public function it_validates_auth_type()
    {
        $this->config = FakeConfig::make(
            authType: null
        );

        try {
            $this->obj = new OAuthToken($this->config);
            $this->assertTrue(false);
        } catch (InvalidAuthTypeException) {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function it_validates_auth_http_response()
    {
        Cache::flush();
        $this->mockHttpAuthFailedResponse();
        $this->buildConfigAndObj();

        try {
            $this->obj->authorize();
            $this->assertTrue(false);
        } catch (TokenIssueException) {
            $this->assertTrue(true);
        }
    }
}
