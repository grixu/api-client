<?php

namespace Grixu\ApiClient\Data;

use Grixu\ApiClient\Contracts\FetchedData;
use Grixu\ApiClient\Exceptions\DamagedResponse;
use Illuminate\Http\Client\Response;

class PaginatedData implements FetchedData
{
    protected array $data;
    protected int $currentPage;
    protected int $totalPages;
    protected int $perPage;

    public function __construct(protected Response $response)
    {
        $this->validateResponse($response);

        $responseData = $response->json();
        $this->validateResponseData($responseData);

        $this->data = $responseData['data'];
        $this->currentPage = $responseData['meta']['current_page'] ?? 1;
        $this->totalPages = $responseData['meta']['last_page'] ?? 1;
        $this->perPage = $responseData['meta']['per_page'] ?? count($this->data);
    }

    protected function validateResponse(Response $response): void
    {
        if (!$response->successful()) {
            throw new DamagedResponse();
        }
    }

    protected function validateResponseData(array $responseData): void
    {
        if (!isset($responseData['data']) || empty($responseData['data'])) {
            throw new DamagedResponse();
        }

        if (!isset($responseData['meta']) || empty($responseData['meta'])) {
            throw new DamagedResponse();
        }
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function isMoreToLoad(): bool
    {
        return $this->currentPage < $this->totalPages;
    }
}
