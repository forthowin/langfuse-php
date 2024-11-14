<?php

namespace Langfuse\Middleware;

use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Langfuse\Client\LangfuseClient;
use GuzzleHttp\Promise\PromiseInterface;

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
            function (RequestInterface $request, array $options) {
                $this->langfuseClient->processRequest($request);
            },
            function (RequestInterface $request, array $options, PromiseInterface $promise) {
                $promise->then(
                    function (ResponseInterface $response) use ($request) {
                        $this->langfuseClient->processResponse($response, $request);
                    },
                    function ($reason) use ($request) {
                        // Handle exceptions if needed
                        // $reason may be an Exception
                        error_log('Request failed: ' . $reason);
                    }
                );
            }
        );
    }
}
