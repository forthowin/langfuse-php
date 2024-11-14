<?php

namespace Langfuse\Config;

use Webmozart\Assert\Assert;

class Config
{
    // Public constants for config keys
    public const PUBLIC_KEY = 'public_key';
    public const SECRET_KEY = 'secret_key';
    public const LANGFUSE_BASE_URI = 'langfuse_base_uri';

    private static $allowedKeys = [
        self::PUBLIC_KEY,
        self::SECRET_KEY,
        self::LANGFUSE_BASE_URI,
    ];

    private $settings = [];

    public function __construct(array $config)
    {
        // Validate keys using Assert
        foreach ($config as $key => $value) {
            Assert::oneOf($key, self::$allowedKeys, "Invalid config key: $key");
        }

        // Set config values
        $this->settings = $config;

        // Validate required keys
        Assert::keyExists($this->settings, self::PUBLIC_KEY, 'The public_key is required.');
        Assert::keyExists($this->settings, self::SECRET_KEY, 'The secret_key is required.');
    }

    public function get(string $key, $default = null)
    {
        Assert::oneOf($key, self::$allowedKeys, "Invalid config key: $key");

        return $this->settings[$key] ?? $default;
    }
}
