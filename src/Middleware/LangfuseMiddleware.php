<?php

namespace Langfuse\Middleware;

use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Langfuse\Client\LangfuseClient;

class LangfuseMiddleware
{
    private LangfuseClient $langfuseClient;

    public function __construct(LangfuseClient $langfuseClient)
    {
        $this->langfuseClient = $langfuseClient;
    }

    public function create(): callable
    {
        return Middleware::tap(
            function (RequestInterface $request) {
                $this->langfuseClient->send('request', $request);
            },
            function (RequestInterface $request, ResponseInterface $response) {
                $this->langfuseClient->send('response', $response);
            }
        );
    }
}
