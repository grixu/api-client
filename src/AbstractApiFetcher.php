<?php

namespace Grixu\ApiClient;

use Closure;
use Grixu\ApiClient\Contracts\ApiFetcher;
use Grixu\ApiClient\Contracts\Config;
use Grixu\ApiClient\Contracts\TokenAuth;
use Illuminate\Support\Collection;

abstract class AbstractApiFetcher implements ApiFetcher
{
    protected ?TokenAuth $token;

    protected Collection $dataCollection;

    public function __construct(
        private Config $config,
        private string $path
    ) {
        $tokenClassName = config('api-client.auth_types')[$config->getAuthType()->value];
        $this->token = new $tokenClassName($config);

        $this->dataCollection = collect();
    }

    protected function callClosure(?Closure $closure)
    {
        if ($closure) {
            $closure->call($this);
        }
    }

    public function getDataCollection(): Collection
    {
        return $this->dataCollection;
    }

    public function parse(string $dtoClass)
    {
        $parserClassName = $this->config->getResponseParserClass();
        $parser = new $parserClassName($dtoClass);

        $flattenData = $this->dataCollection->flatten(1);
        return $parser->parse($flattenData);
    }
}
