# Langfuse OpenAI Middleware for PHP

## Introduction

This library provides middleware for intercepting OpenAI API requests and responses in PHP applications, sending them to Langfuse for monitoring and analysis. It is designed to be easily integrated into Symfony projects, leveraging Symfony's dependency injection mechanism.

## Installation

Install the library and required dependencies via Composer:

```
composer require langfuse/langfuse-openai-middleware
composer require ramsey/uuid
```

## Configuration in Symfony

### Step 1: Define Environment Variables

In your `.env` file, add your Langfuse `PUBLIC_KEY` and `SECRET_KEY`, and your OpenAI API key:

```
LANGFUSE_PUBLIC_KEY=your-public-key
LANGFUSE_SECRET_KEY=your-secret-key
OPENAI_API_KEY=your-openai-api-key
```

### Step 2: Register Services

In your `config/services.yaml`, add the following service definitions:

```
parameters:
    langfuse_config:
        public_key: '%env(LANGFUSE_PUBLIC_KEY)%'
        secret_key: '%env(LANGFUSE_SECRET_KEY)%'
        # Optional: langfuse_base_uri: 'https://custom.langfuse.endpoint/'

    openai_api_key: '%env(OPENAI_API_KEY)%'

services:
    Langfuse\Config\Config:
        class: Langfuse\Config\Config
        arguments:
            - '%langfuse_config%'
        public: false

    Langfuse\Client\OpenAiFactory:
        class: Langfuse\Client\OpenAiFactory
        arguments:
            - '@Langfuse\Config\Config'
            - '%openai_api_key%'
        public: true

    App\Service\OpenAIService:
        arguments:
            - '@Langfuse\Client\OpenAiFactory'
```

### Step 3: Use the OpenAI Client in Your Services

Now, you can inject `OpenAiFactory` into your services or controllers.

**Example:**

```
namespace App\Service;

use Langfuse\Client\OpenAiFactory;

class OpenAIService
{
private $openAIClient;

    public function __construct(OpenAiFactory $openAIFactory)
    {
        $this->openAIClient = $openAIFactory->make();
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
