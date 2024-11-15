<?php

namespace Langfuse\Event;

use DateTimeInterface;
use Ramsey\Uuid\Uuid;

abstract class IngestionEvent
{
    protected $type;
    protected $id;
    protected $timestamp;
    protected $body;

    public function __construct(string $type, string $id, string $timestamp, array $body)
    {
        $this->type = $type;
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->body = $body;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'id' => $this->id,
            'timestamp' => $this->timestamp,
            'body' => $this->body,
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }
}
