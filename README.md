# API Client

Simple API Client with OAuth2 Auth handler.

## Installation

You can install the package via composer:

```bash
composer require grixu/api-client
```

## Usage for JSON API

### Create configuration object

```php
use Grixu\ApiClient\Config\JsonApiConfig;
use Grixu\ApiClient\Data\PaginatedData;
use Grixu\ApiClient\Data\StraightKeyParser;

$config =  new JsonApiConfig(
            baseUrl: 'http://rywal.com.pl',
            responseDataClass: PaginatedData::class,
            responseParserClass: StraightKeyParser::class,
            authType: 'oAuth2',  // or you can use enum: AuthType::OAUTH2()
            authUrl: 'http://rywal.com.pl',
            authData: ['key', 'secret'],
            paginationParam: 'page',
            filters: ['list', 'of', 'param', 'names', 'that', 'could', 'be', 'used', 'as', 'filters'],
            includes: ['same', 'but', 'for', 'includes'],
            sorts: ['same', 'this', 'time', 'for', 'sort', 'options']
        );
```

If you have various values of filter names, or extensive API to handle - consider creating Factory which will be
handling creating `JsonApiConfig`. Or keep them in separate config file.

### Create fetcher

```php
use Grixu\ApiClient\JsonApiFetcher;

$fetcher = new JsonApiFetcher($config, '/api/path');
```

Here, you can adjust your query using `UrlCompose` by adding filters, sorts, includes:

```php
// in every example you could pass multiple values
$fetcher->compose()->addFilter('filter_name', 'filter_value_1');
$fetcher->compose()->addInclude('include', 'include_relationship_1', 'include_relationship_2');
$fetcher->compose()->addSort('sort', 'sort_field');

//also you could set page in pagination
$fetcher->compose()->setPage('page', 2);
// or simply move to next page by hand
$fetcher->composer->nextPage();
```

#### Fetch Data

```php
$fetcher->fetch();
$parsedCollection = $parser->parse(DtoClass::class);
```

`$parsedCollection` is `\Illuminate\Support\Collection` filled with DTOs you

## Configuration

You can adjust global configuration of APIClient in your `.env` file:

```dotenv
API_ERROR_LOGGING=true
API_ERROR_LOG_CHANNEL="api-client"
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mg@grixu.dev instead of using the issue tracker.

## Credits

- [Mateusz Gostański](https://github.com/grixu)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
