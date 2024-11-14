<?php

namespace Langfuse\Client;

use OpenAI\Factory as OriginalOpenAIFactory;
use OpenAI\Client as OpenAIClient;
use Langfuse\Config\Config;
use Langfuse\Middleware\LangfuseMiddleware;

class OpenAiFactory
{
    private OriginalOpenAIFactory $originalOpenAIFactory;

    public function __construct(
        private readonly Config $langfuseConfig
    )
    {
        $this->originalOpenAIFactory = new OriginalOpenAIFactory();
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
     * @return OpenAIClient
     */
    public function make(): OpenAIClient
    {
        $langfuseClient = new LangfuseClient($this->langfuseConfig);
        $langfuseMiddleware = new LangfuseMiddleware($langfuseClient);
        $customGuzzleClient = GuzzleClientFactory::create($langfuseMiddleware);

        return $this->originalOpenAIFactory
            ->withHttpClient($customGuzzleClient)
            ->make();
    }
}
