<?php

namespace Grixu\ApiClient;

use Illuminate\Support\Facades\Facade;

class ApiClientFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'api-client';
    }
}
