<?php

namespace Grixu\ApiClient\Tests\Data;

use Grixu\ApiClient\Data\PaginatedData;
use Grixu\ApiClient\Exceptions\DamagedResponse;
use Grixu\ApiClient\Tests\Helpers\HttpMocksTrait;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;

class PaginatedDataTest extends TestCase
{
    use HttpMocksTrait;

    protected PaginatedData $obj;

    /** @test */
    public function it_constructs()
    {
        $this->createObjAndFakeHttp200();
        $this->assertEquals(PaginatedData::class, $this->obj::class);
    }

    protected function createObjAndFakeHttp200()
    {
        $this->mockHttpSuccessfulResponse();
        $response = Http::get('http://rywal.com.pl');

        $this->obj = new PaginatedData($response);
    }

    /** @test */
    public function it_validate_response()
    {
        try {
            $this->createObjAndFakeHttp404();
            $this->assertTrue(false);
        } catch (DamagedResponse) {
            $this->assertTrue(true);
        }
    }

    protected function createObjAndFakeHttp404()
    {
        $this->mockHttp404Response();
        $response = Http::get('http://rywal.com.pl');

        $this->obj = new PaginatedData($response);
    }

    /** @test */
    public function it_validates_response_schema()
    {
        try {
            $this->createObjAndFakeWrongDataKeyName();
            $this->assertTrue(false);
        } catch (DamagedResponse) {
            $this->assertTrue(true);
        }
    }

    protected function createObjAndFakeWrongDataKeyName()
    {
        Http::fake(
            [
                '*' => Http::response(
                    [
                        'data' => [
                            'some',
                            'data',
                            'here'
                        ],
                        'shit' => [
                            'current_page' => 1,
                            'total' => 10,
                            'per_page' => 3,
                        ]
                    ],
                    200
                )
            ]
        );

        $response = Http::get('http://rywal.com.pl');

        $this->obj = new PaginatedData($response);
    }

    /** @test */
    public function it_validates_response_schema_deeply()
    {
        try {
            $this->createObjAndFakeWrongDataKeyInDataSection();
            $this->assertTrue(false);
        } catch (DamagedResponse) {
            $this->assertTrue(true);
        }
    }

    protected function createObjAndFakeWrongDataKeyInDataSection()
    {
        Http::fake(
            [
                '*' => Http::response(
                    [
                        'shit' => [
                            'some',
                            'data',
                            'here'
                        ],
                        'meta' => [
                            'current_page' => 1,
                            'total' => 10,
                            'per_page' => 3,
                        ]
                    ],
                    200
                )
            ]
        );

        $response = Http::get('http://rywal.com.pl');

        $this->obj = new PaginatedData($response);
    }

    /** @test */
    public function it_returns_data()
    {
        $this->createObjAndFakeHttp200();

        $returnedData = $this->obj->getData();

        $this->assertNotEmpty($returnedData);
        $this->assertIsArray($returnedData);
        $this->assertCount(3, $returnedData);
    }

    /** @test */
    public function it_returns_current_page()
    {
        $this->createObjAndFakeHttp200();

        $returnedData = $this->obj->getCurrentPage();

        $this->assertIsInt($returnedData);
        $this->assertEquals(1, $returnedData);
    }

    /** @test */
    public function it_returns_total_pages()
    {
        $this->createObjAndFakeHttp200();

        $returnedData = $this->obj->getTotalPages();

        $this->assertIsInt($returnedData);
        $this->assertEquals(10, $returnedData);
    }

    /** @test */
    public function it_returns_per_page()
    {
        $this->createObjAndFakeHttp200();

        $returnedData = $this->obj->getPerPage();

        $this->assertIsInt($returnedData);
        $this->assertEquals(3, $returnedData);
    }
}
