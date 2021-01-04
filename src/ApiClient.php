<?php

namespace Grixu\ApiClient;

use Grixu\ApiClient\Exceptions\WrongConfigException;

class ApiClient
{
    public static function make(...$config): CallApi
    {
        if (count($config) === 1 && is_array(config($config[0]))) {
            $config = config($config[0]);
        }

        if (empty($config) || count($config) < 4) {
            throw new WrongConfigException();
        }

        return new CallApi(...$config);
    }
}
