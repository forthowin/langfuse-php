<?php

namespace Langfuse;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class LangfuseClient
{
    private $baseUrl = 'https://api.langfuse.com';
    private $client;

    public function __construct(string $publicKey, string $secretKey, Client $client = null)
    {
        $auth = base64_encode($publicKey . ':' . $secretKey);
        $this->client = $client ?: new Client(
            [
                'base_uri' => $this->baseUrl,
                'headers' => [
                    'Authorization' => 'Basic ' . $auth,
                    'Content-Type' => 'application/json'
                ]
            ]
        );
    }

    public function post(string $endpoint, array $data): array
    {
        try {
            $response = $this->client->post($endpoint, [
                'json' => $data
            ]);
            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);

            return ['status' => $statusCode, 'response' => $body];
        } catch (GuzzleException $e) {
            return ['status' => $e->getCode(), 'response' => $e->getMessage()];
        }
    }
}
