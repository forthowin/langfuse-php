<?php

namespace Langfuse\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use Langfuse\Middleware\LangfuseMiddleware;

class GuzzleClientFactory
{
    public static function create(LangfuseMiddleware $middleware): GuzzleClient
    {
        $handlerStack = HandlerStack::create();
        $handlerStack->push($middleware->create());

        return new GuzzleClient(['handler' => $handlerStack]);
    }
}
