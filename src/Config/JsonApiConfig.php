<?php

namespace Grixu\ApiClient\Config;

use Grixu\ApiClient\Auth\AuthData;
use Grixu\ApiClient\Enums\AuthType;
use Grixu\ApiClient\Contracts\JsonApiConfig as JsonApiConfigInterface;

class JsonApiConfig extends BaseConfig implements JsonApiConfigInterface
{
    protected string $paginationParam;
    protected array $filters;
    protected array $includes;
    protected array $sorts;

    public function __construct(
        string $baseUrl,
        string $responseDataClass,
        string $responseParserClass,
        AuthType|string|null $authType = null,
        ?string $authUrl = null,
        array|AuthData|null $authData = null,
        string $paginationParam = 'page',
        array $filters = [],
        array $includes = [],
        array $sorts = []
    ) {
        parent::__construct($baseUrl, $responseDataClass, $responseParserClass, $authType, $authUrl, $authData);

        $this->paginationParam = $paginationParam;
        $this->filters = $filters;
        $this->includes = $includes;
        $this->sorts = $sorts;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getIncludes(): array
    {
        return $this->includes;
    }

    public function getSorts(): array
    {
        return $this->sorts;
    }

    public function getPaginationParam(): string
    {
        return $this->paginationParam;
    }
}
