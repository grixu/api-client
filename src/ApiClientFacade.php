<?php

namespace Grixu\ApiClient;

use Illuminate\Support\Facades\Facade;

/**
 * @method static make(mixed $env)
 */
class ApiClientFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'api-client';
    }
}
