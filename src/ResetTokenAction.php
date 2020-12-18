<?php

namespace Grixu\ApiClient;

use Illuminate\Support\Facades\Cache;

class ResetTokenAction
{
    private GetTokenAction $action;

    public function __construct()
    {
        $this->action = new GetTokenAction();
    }

    public function execute(): string
    {
        Cache::forget('api-client-token');
        return $this->action->execute();
    }
}
