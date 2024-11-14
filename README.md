# Langfuse OpenAI Middleware for PHP

## Introduction

This library provides middleware for intercepting OpenAI API requests and responses in PHP applications, sending them to Langfuse for monitoring and analysis. It is designed to be easily integrated into Symfony projects, leveraging Symfony's dependency injection mechanism.

## Installation

Install the library via Composer:

```
composer require langfuse/langfuse-openai-middleware
```

## Configuration in Symfony

### Step 1: Define Environment Variables

In your `.env` file, add your Langfuse `PUBLIC_KEY` and `SECRET_KEY`:

```
LANGFUSE_PUBLIC_KEY=your-public-key
LANGFUSE_SECRET_KEY=your-secret-key
```

### Step 2: Register Services

In your `config/services.yaml`, add the following service definitions:

```
# config/services.yaml

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
        class: Langfuse\Client\LangfuseClient
        arguments:
            - '@Langfuse\Config\Config'
        public: false

    Langfuse\Middleware\LangfuseMiddleware:
        class: Langfuse\Middleware\LangfuseMiddleware
        arguments:
            - '@Langfuse\Client\LangfuseClient'
        public: false

    Langfuse\Client\GuzzleClientFactory:
        class: GuzzleHttp\Client
        factory: [Langfuse\Client\GuzzleClientFactory, create]
        arguments:
            - '@Langfuse\Middleware\LangfuseMiddleware'
        public: false

    OpenAI\Client:
        class: OpenAI\Client
        arguments:
            - '%env(OPENAI_API_KEY)%'
            - { http_client: '@Langfuse\Client\GuzzleClientFactory' }
        public: true
```

### Step 3: Use the OpenAI Client in Your Services

Now, you can inject `OpenAI\Client` into your services or controllers as needed.

**Example:**

```
namespace App\Service;

use OpenAI\Client;

class OpenAIService
{
private $openAIClient;

    public function __construct(Client $openAIClient)
    {
        $this->openAIClient = $openAIClient;
    }

    public function performAction()
    {
        $response = $this->openAIClient->chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'user', 'content' => 'Hello!'],
            ],
        ]);

        // Your logic here
    }
}
```

### Step 4: Ensure Environment Variables are Set

In your `.env` file, make sure you have:

```
OPENAI_API_KEY=your-openai-api-key
LANGFUSE_PUBLIC_KEY=your-public-key
LANGFUSE_SECRET_KEY=your-secret-key
```

## Advanced Configuration

If you need to set a custom Langfuse base URI, you can add it to the `langfuse_config` parameter:

```
parameters:
langfuse_config:
public_key: '%env(LANGFUSE_PUBLIC_KEY)%'
secret_key: '%env(LANGFUSE_SECRET_KEY)%'
langfuse_base_uri: 'https://custom.langfuse.endpoint/'
```

## Error Handling

The middleware handles exceptions internally and logs any errors. You can adjust the error handling in `LangfuseClient.php` if needed.

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for any improvements or bugs.

## License

This project is licensed under the MIT License.
