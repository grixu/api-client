<?php

namespace Grixu\ApiClient;

class ApiClient
{
    private CallApiAction $callApiAction;

    public function __construct()
    {
        $this->callApiAction = new CallApiAction();
    }

    public function make(string $url)
    {
        return $this->callApiAction->execute($url);
    }
}
