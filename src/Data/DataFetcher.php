<?php

namespace Grixu\ApiClient\Data;

use Grixu\ApiClient\Contracts\FetchedData;
use Grixu\ApiClient\Contracts\TokenAuth;
use Grixu\ApiClient\Exceptions\AccessDeniedException;
use Grixu\ApiClient\Exceptions\ApiCallException;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class DataFetcher
{
    protected FetchedData $fetchedData;

    public function __construct(protected Uri $uri, protected string $responseClass, protected ?TokenAuth $token = null)
    {
    }

    public function get(): FetchedData
    {
        if (empty($this->fetchedData)) {
            $this->fetch();
        }

        return $this->fetchedData;
    }

    public function fetch(): void
    {
        $response = $this->makeRequest();

        $this->handleHttpErrors($response);

        if ($response->successful()) {
            $this->fetchedData = new $this->responseClass($response);
        }
    }

    protected function makeRequest(): Response
    {
        $request = Http::withHeaders(
            [
                'Accept' => 'application/json',
            ]
        )->timeout(config('api-client.timeout'));

        if ($this->token) {
            $request = $request->withToken($this->token->getToken());
        }

        return $request->get($this->uri);
    }

    protected function handleHttpErrors(Response $response)
    {
        if ($response->failed()) {
            match ($response->status()) {
                401 => $this->handleUnauthorized(),
                403 => throw new AccessDeniedException($response->body()),
                default => throw new ApiCallException($this->uri, $response->body())
            };
        }
    }

    protected function handleUnauthorized(): void
    {
        $this->token->reset();
        $this->fetch();
    }
}
