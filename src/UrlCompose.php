<?php

namespace Grixu\ApiClient;

use Grixu\ApiClient\Builders\ArrayBasedUrlBuilder;
use Grixu\ApiClient\Builders\CommaSeparatedValueUrlBuilder;
use Grixu\ApiClient\Builders\PageUrlBuilder;
use Grixu\ApiClient\Contracts\JsonApiConfig;
use GuzzleHttp\Psr7\Uri;

class UrlCompose
{
    private ArrayBasedUrlBuilder $filterBuilder;
    private CommaSeparatedValueUrlBuilder $includeBuilder;
    private CommaSeparatedValueUrlBuilder $sortBuilder;
    private PageUrlBuilder $pageBuilder;

    public function __construct(private JsonApiConfig $config, private string $path)
    {
        $this->filterBuilder = new ArrayBasedUrlBuilder($config->getFilters());
        $this->includeBuilder = new CommaSeparatedValueUrlBuilder($config->getIncludes());
        $this->sortBuilder = new CommaSeparatedValueUrlBuilder($config->getSorts());
        $this->pageBuilder = new PageUrlBuilder();
    }

    public function get(): Uri
    {
        $query = collect(
            [
                $this->filterBuilder->get(),
                $this->sortBuilder->get(),
                $this->includeBuilder->get(),
                $this->pageBuilder->get(),
            ]
        )
            ->filter()
            ->join('&');

        return $this->config->getBaseUrl()
            ->withPath($this->path)
            ->withQuery($query);
    }

    public function addFilter(string $filterName, ...$filterParams): static
    {
        $this->filterBuilder->add($filterName, ...$filterParams);

        return $this;
    }

    public function addInclude(string $includeParamName, ...$includes): static
    {
        $this->includeBuilder->add($includeParamName, ...$includes);

        return $this;
    }

    public function addSort(string $sortParamName, ...$sorts): static
    {
        $this->sortBuilder->add($sortParamName, ...$sorts);

        return $this;
    }

    public function nextPage(): static
    {
        if (empty($this->pageBuilder->get())) {
            $this->pageBuilder->add('page', 1);
        }

        $this->pageBuilder->next();

        return $this;
    }

    public function setPage(string $param, int $page): static
    {
        $this->pageBuilder->add($param, $page);

        return $this;
    }
}
