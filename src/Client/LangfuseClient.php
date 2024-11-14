<?php

namespace Langfuse\Client;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\MessageInterface;
use Langfuse\Config\Config;

class LangfuseClient
{
    private GuzzleClient $httpClient;

    public function __construct(Config $config)
    {
        $baseUri = $config->get(Config::LANGFUSE_BASE_URI, 'https://cloud.langfuse.com/');
        $publicKey = $config->get(Config::PUBLIC_KEY);
        $secretKey = $config->get(Config::SECRET_KEY);

        $this->httpClient = new GuzzleClient(
            [
                'base_uri' => $baseUri,
                'auth' => [$publicKey, $secretKey],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );
    }

    public function send(string $type, MessageInterface $message): void
    {
        // Prepare payload
        $payload = [
            'type' => $type,
            'data' => [
                'method' => $message->getMethod(),
                'uri' => (string)$message->getUri(),
                'headers' => $message->getHeaders(),
                'body' => (string)$message->getBody(),
            ],
        ];

        // Send data asynchronously
        $this->httpClient->postAsync('/api/v1/ingest', [
            'json' => $payload,
        ])->then(
            function ($response) {
                // Handle success if needed
            },
            function ($exception) {
                // Handle exceptions if needed
                error_log('Langfuse send error: ' . $exception->getMessage());
            }
        );
    }
}
