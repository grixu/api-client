<?php

namespace Grixu\ApiClient;

use Closure;
use Grixu\ApiClient\Contracts\JsonApiConfig;
use Grixu\ApiClient\Data\DataFetcher;

class JsonApiFetcher extends AbstractApiFetcher
{
    private UrlCompose $urlComposer;

    public function __construct(
        private JsonApiConfig $config,
        private string $path,
        private bool $loadAll = true,
        private int $page = 1
    ) {
        parent::__construct($config, $path);

        $this->urlComposer = new UrlCompose($config, $path);
        $this->urlComposer->setPage($config->getPaginationParam(), $page);
    }

    public function compose(): UrlCompose
    {
        return $this->urlComposer;
    }

    public function makeDataFetcher(): DataFetcher
    {
        return new DataFetcher(
            $this->urlComposer->get(),
            $this->config->getResponseDataClass(),
            $this->token
        );
    }

    public function getUrlCompose(): UrlCompose
    {
        return $this->urlComposer;
    }

    public function fetch(?Closure $before = null)
    {
        $this->callClosure($before);

        $dataFetcher = $this->makeDataFetcher();
        $dataFetcher->fetch();

        $results = $dataFetcher->get();
        $this->dataCollection->push($results->getData());

        if ($results->isMoreToLoad() && $this->loadAll) {
            $this->urlComposer->nextPage();
            $this->fetch($before);
        }
    }

    public function chunk(Closure $loop)
    {
        $dataFetcher = new DataFetcher(
            $this->urlComposer->get(),
            $this->config->getResponseDataClass(),
            $this->token
        );

        $dataFetcher->fetch();
        $results = $dataFetcher->get();

        $loop($results->getDataCollection());

        if ($results->isMoreToLoad() && $this->loadAll) {
            $this->urlComposer->nextPage();
            $this->chunk($loop);
        }
    }
}
