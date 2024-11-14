<?php

namespace Langfuse\Middleware;

use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Langfuse\Client\LangfuseClient;

class LangfuseMiddleware
{
    private $langfuseClient;

    public function __construct(LangfuseClient $langfuseClient)
    {
        $this->langfuseClient = $langfuseClient;
    }

    public function create()
    {
        return Middleware::tap(
            function (RequestInterface $request) {
                $this->langfuseClient->processRequest($request);
            },
            function (RequestInterface $request, ResponseInterface $response) {
                $this->langfuseClient->processResponse($response, $request);
            }
        );
    }
}
