<?php

namespace Grixu\ApiClient\Tests\Helpers;

use Grixu\ApiClient\Auth\AuthData;
use Grixu\ApiClient\Config\JsonApiConfig;
use Grixu\ApiClient\Data\PaginatedData;
use Grixu\ApiClient\Data\StraightKeyParser;
use Grixu\ApiClient\Enums\AuthType;

class FakeConfig
{
    public static function make(
        string|AuthType|null $authType = 'oAuth2',
        array|AuthData|null $authData = ['key', 'secret'],
        ?array $filters = [],
        ?array $includes = [],
        ?array $sorts = []
    ): JsonApiConfig {
        return new JsonApiConfig(
            baseUrl: 'http://rywal.com.pl',
            responseDataClass: PaginatedData::class,
            responseParserClass: StraightKeyParser::class,
            authType: $authType,
            authUrl: 'http://rywal.com.pl',
            authData: $authData,
            paginationParam: 'page',
            filters: $filters,
            includes: $includes,
            sorts: $sorts
        );
    }
}
