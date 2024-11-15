# Langfuse OpenAI Middleware for PHP

## Introduction

This library provides wraper functions to use Langfuse LLM monitoring for your application. 
It was build for Symfony but can be used in any PHP application.

## Installation

Install the library and required dependencies via Composer:

```
composer require janzaba/langfuse
```

## Configuration in Symfony

### Step 1: Define Environment Variables

In your `.env` file, add your Langfuse `PUBLIC_KEY` and `SECRET_KEY`, and your OpenAI API key:

```bash
LANGFUSE_PUBLIC_KEY=your-public-key
LANGFUSE_SECRET_KEY=your-secret-key
```

### Step 2: Register Services

In your `config/services.yaml`, add the following service definitions:

```yaml
parameters:
    langfuse_config:
        public_key: '%env(LANGFUSE_PUBLIC_KEY)%'
        secret_key: '%env(LANGFUSE_SECRET_KEY)%'
        # Optional: langfuse_base_uri: 'https://custom.langfuse.endpoint/'

services:
        Langfuse\Config\Config:
        class: Langfuse\Config\Config
        arguments:
            - '%langfuse_config%'
        public: false

    Langfuse\Client\LangfuseClient:
        arguments:
            $config: '@Langfuse\Config\Config'

    Langfuse\LangfuseManager:
        arguments:
            $langfuseClient: '@Langfuse\Client\LangfuseClient'
```

### Step 3: Use the OpenAI Client in Your Services

Now you can wrap your code with helper methods

#### Trace

```php
$this->langfuseManager->withTrace(
    'Trace name',
    ['operation' => 'example operation name'],
    function () {
        // Your code here
    }
);
```

#### Generation

Inside a trace you can have LLM generation.

```php

$answer = $this->langfuseManager->withGeneration(
    'prompt name', 
    'gpt-4o-mini', 
    $prompt, 
    function () use ($prompt) {
        return $this->openAIClient->chat()->create(
            [
                'model' => 'gpt-4o-mini',
                'messages' => $prompt,
            ]
        );
    }
);
```

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for any improvements or bugs.

## License

This project is licensed under the MIT License.
