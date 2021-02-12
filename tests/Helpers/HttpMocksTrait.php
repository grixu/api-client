<?php

namespace Grixu\ApiClient\Tests\Helpers;

use Illuminate\Support\Facades\Http;

trait HttpMocksTrait
{
    protected function mockHttpSuccessfulResponse(): void
    {
        Http::fake(
            [
                '*' => Http::response(
                    [
                        'data' => [
                            'data' => [
                                'some',
                                'data',
                                'here'
                            ],
                            'current_page' => 1,
                            'last_page' => 10,
                            'per_page' => 3,
                        ]
                    ],
                    200
                )
            ]
        );
    }

    protected function mockHttpSinglePageDataResponseSequence(): void
    {
        Http::fake(
            [
                '*' => Http::sequence()
                    ->push(
                        [
                            'access_token' => 'blebleble'
                        ],
                        200
                    )
                    ->push(
                        [
                            'data' => [
                                'data' => [
                                    'some',
                                    'data',
                                    'here'
                                ],
                                'current_page' => 1,
                                'last_page' => 1,
                                'per_page' => 3,
                            ]
                        ],
                        200
                    )
                    ->push(
                        [
                            'data' => [
                                'data' => [
                                    'some',
                                    'data',
                                    'here'
                                ],
                                'current_page' => 1,
                                'last_page' => 1,
                                'per_page' => 3,
                            ]
                        ],
                        200
                    )
            ]
        );
    }

    protected function mockHttpMultiplePagesDataResponseSequence(): void
    {
        Http::fake(
            [
                '*' => Http::sequence()
                    ->push(
                        [
                            'access_token' => 'blebleble'
                        ],
                        200
                    )
                    ->push(
                        [
                            'data' => [
                                'data' => [
                                    'some',
                                    'data',
                                    'here'
                                ],
                                'current_page' => 1,
                                'last_page' => 4,
                                'per_page' => 3,
                            ]
                        ],
                        200
                    )
                    ->push(
                        [
                            'data' => [
                                'data' => [
                                    'some',
                                    'data',
                                    'here'
                                ],
                                'current_page' => 2,
                                'last_page' => 4,
                                'per_page' => 3,
                            ]
                        ],
                        200
                    )
                    ->push(
                        [
                            'data' => [
                                'data' => [
                                    'some',
                                    'data',
                                    'here'
                                ],
                                'current_page' => 3,
                                'last_page' => 4,
                                'per_page' => 3,
                            ]
                        ],
                        200
                    )
                    ->push(
                        [
                            'data' => [
                                'data' => [
                                    'some',
                                    'data',
                                    'here'
                                ],
                                'current_page' => 4,
                                'last_page' => 4,
                                'per_page' => 3,
                            ]
                        ],
                        200
                    )
            ]
        );
    }

    protected function mockHttpSinglePageRealDataResponseSequence(): void
    {
        Http::fake(
            [
                '*' => Http::sequence()
                    ->push(
                        [
                            'access_token' => 'blebleble'
                        ],
                        200
                    )
                    ->push(
                        [
                            'data' => [
                                'data' => [
                                    [
                                        'first' => 'First value',
                                        'second' => 'Second value',
                                        'third' => 'Third value'
                                    ],
                                    [
                                        'first' => 'First value',
                                        'second' => 'Second value',
                                        'third' => 'Third value'
                                    ],
                                    [
                                        'first' => 'First value',
                                        'second' => 'Second value',
                                        'third' => 'Third value'
                                    ]
                                ],
                                'current_page' => 1,
                                'last_page' => 1,
                                'per_page' => 10,
                            ]
                        ],
                        200
                    )
            ]
        );
    }

    protected function mockHttpSequenceWith401(): void
    {
        Http::fake(
            [
                '*' => Http::sequence()
                    ->push(
                        [
                            'Unauthorized'
                        ],
                        401
                    )
                    ->push(
                        [
                            'access_token' => 'blebleble'
                        ],
                        200
                    )
                    ->push(
                        [
                            'data' => [
                                'data' => [
                                    'some',
                                    'data',
                                    'here'
                                ],
                                'current_page' => 1,
                                'last_page' => 10,
                                'per_page' => 3,
                            ]
                        ],
                        200
                    )

            ]
        );
    }

    protected function mockHttp403Response()
    {
        Http::fake(
            [
                '*' => Http::response(
                    [
                        'No access allowed'
                    ],
                    403
                )
            ]
        );
    }

    protected function mockHttp404Response()
    {
        Http::fake(
            [
                '*' => Http::response(
                    [
                        'Not found'
                    ],
                    404
                )
            ]
        );
    }

    protected function mockHttpAuthResponse()
    {
        Http::fake(
            [
                '*' => Http::response(
                    [
                        'access_token' => 'blebleble'
                    ],
                    200
                )
            ]
        );
    }

    protected function mockHttpAuthFailedResponse()
    {
        Http::fake(
            [
                '*' => Http::response(
                    [
                        'Shit happens'
                    ],
                    500
                )
            ]
        );
    }
}
