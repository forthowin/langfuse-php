<?php

namespace Langfuse\Client;

use OpenAI\Factory as OriginalOpenAIFactory;
use OpenAI\Client as OpenAIClient;
use Langfuse\Config\Config;
use Langfuse\Client\LangfuseClient;
use Langfuse\Middleware\LangfuseMiddleware;

class OpenAiFactory
{
    private OriginalOpenAIFactory $originalOpenAIFactory;
    private array $langfuseConfig;

    public function __construct(array $langfuseConfig)
    {
        $this->originalOpenAIFactory = new OriginalOpenAIFactory();
        $this->langfuseConfig = $langfuseConfig;
    }

    /**
     * Sets the API key for the OpenAI client.
     *
     * @param string $apiKey
     * @return self
     */
    public function withApiKey(string $apiKey): self
    {
        $this->originalOpenAIFactory = $this->originalOpenAIFactory->withApiKey($apiKey);
        return $this;
    }

    /**
     * Creates the OpenAI client with the custom HTTP client including the Langfuse middleware.
     *
     * @param array $config
     * @return OpenAIClient
     */
    public function make(array $config = []): OpenAIClient
    {
        $langfuseConfig = new Config($this->langfuseConfig);
        $langfuseClient = new LangfuseClient($langfuseConfig);
        $langfuseMiddleware = new LangfuseMiddleware($langfuseClient);
        $customGuzzleClient = GuzzleClientFactory::create($langfuseMiddleware);

        return $this->originalOpenAIFactory
            ->withHttpClient($customGuzzleClient)
            ->make();
    }
}
