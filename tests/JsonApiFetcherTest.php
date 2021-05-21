<?php

namespace Grixu\ApiClient\Tests;

use Grixu\ApiClient\JsonApiFetcher;
use Grixu\ApiClient\ApiClientServiceProvider;
use Grixu\ApiClient\Tests\Helpers\ExampleDto;
use Grixu\ApiClient\Tests\Helpers\FakeConfig;
use Grixu\ApiClient\Tests\Helpers\HttpMocksTrait;
use Grixu\ApiClient\UrlCompose;
use Illuminate\Support\Facades\Cache;
use Mockery\Mock;
use Orchestra\Testbench\TestCase;

class JsonApiFetcherTest extends TestCase
{
    use HttpMocksTrait;

    protected JsonApiFetcher $obj;

    protected function getPackageProviders($app): array
    {
        return [ApiClientServiceProvider::class];
    }

    /** @test */
    public function it_constructs()
    {
        $this->makeObj();

        $this->assertEquals(JsonApiFetcher::class, $this->obj::class);
    }

    protected function makeObj(bool $fetchAll = true)
    {
        $config = FakeConfig::make();

        $this->obj = new JsonApiFetcher($config, '/api/test', $fetchAll);
    }

    /** @test */
    public function it_fetches_data()
    {
        Cache::flush();
        $this->mockHttpSinglePageDataResponseSequence();
        $this->makeObj(false);

        $this->obj->fetch();

        $this->assertResults(1);
    }

    protected function assertResults(int $count)
    {
        $returnedData = $this->obj->getDataCollection();
        $this->assertNotEmpty($returnedData);
        $this->assertIsArray($returnedData->first());
        $this->assertCount($count, $returnedData);
    }

    /** @test */
    public function it_run_closure_before_fetch()
    {
        $this->makeObj(false);

        try {
            $this->obj->fetch(function () {
                throw new \Exception('Test');
            });
        } catch (\Exception) {
            $this->assertTrue(true);
        }

    }

    /** @test */
    public function it_adding_data_to_collection()
    {
        Cache::flush();
        $this->mockHttpSinglePageDataResponseSequence();
        $this->makeObj(false);

        $this->obj->fetch();
        $this->assertResults(1);

        $this->obj->fetch();
        $this->assertResults(2);
    }

    /** @test */
    public function it_fetches_multiple_times_until_load_all_data()
    {
        Cache::flush();
        $this->mockHttpMultiplePagesDataResponseSequence();
        $this->makeObj(true);

        $this->obj->fetch();
        $this->assertResults(4);
    }

    /** @test */
    public function it_parses_data()
    {
        Cache::flush();
        $this->mockHttpSinglePageRealDataResponseSequence();
        $this->makeObj();

        $this->obj->fetch();
        $returnedData = $this->obj->parse(ExampleDto::class);

        $this->assertCount(3, $returnedData);
    }

    /** @test */
    public function it_give_access_to_underlying_url_compose()
    {
        $this->makeObj();
        $returnedValue = $this->obj->compose();

        $this->assertEquals(UrlCompose::class, $returnedValue::class);
    }

    /** @test */
    public function it_chunk_load()
    {
        Cache::flush();
        $this->mockHttpMultiplePagesDataResponseSequence();
        $this->makeObj(true);

        $closure = \Mockery::mock(fn () => true);
        $this->obj->chunk(fn () => $closure());
        $closure->shouldHaveBeenCalled();
    }
}
