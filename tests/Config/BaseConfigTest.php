<?php

namespace Grixu\ApiClient\Tests\Config;

use Grixu\ApiClient\Auth\AuthData;
use Grixu\ApiClient\Config\BaseConfig;
use Grixu\ApiClient\Config\JsonApiConfig;
use Grixu\ApiClient\Data\PaginatedData;
use Grixu\ApiClient\Data\StraightKeyParser;
use Grixu\ApiClient\Enums\AuthType;
use Orchestra\Testbench\TestCase;

class BaseConfigTest extends TestCase
{
    /** @test */
    public function it_expandable()
    {
        $obj = new class (
            'http://rywal.com.pl',
            'oAuth2',
            'http://rywal.com.pl',
            ['key', 'secret'],
        ) extends BaseConfig {
            protected string $newVal;

            public function __construct(
                string $baseUrl,
                AuthType|string|null $authType,
                ?string $authUrl,
                array|AuthData|null $authData,
                string $newVal = '',
            ) {
                parent::__construct(
                    $baseUrl,
                    PaginatedData::class,
                    StraightKeyParser::class,
                    $authType,
                    $authUrl,
                    $authData,
                );
                $this->newVal = $newVal;
            }
        };

        $this->assertTrue($obj instanceof BaseConfig);
    }

    /** @test */
    public function it_could_be_construct_by_built_in_factory()
    {
        $returnedObj = JsonApiConfig::make(
            [
                'http://rywal.com.pl',
                PaginatedData::class,
                StraightKeyParser::class,
            ]
        );

        $this->assertEquals(JsonApiConfig::class, $returnedObj::class);
    }
}
