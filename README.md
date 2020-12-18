# API Client

Simple API Client with OAuth2 Auth handler. 

## Installation

You can install the package via composer:

```bash
composer require grixu/api-client
```

## Usage

At first set up your `.env` file:
```dotenv
API_BASE_URL=""
API_CLIENT_KEY=""
API_CLIENT_ID=""
API_OAUTH=""
API_ERROR_LOGGING=true
API_ERROR_LOG_CHANNEL="api-client"
```

Then you can just simply call:

``` php
use Grixu\ApiClient\ApiClient;

$reponse = ApiClient::make('/products');
```

### Testing

``` bash
composer test
```

### Changelog

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
