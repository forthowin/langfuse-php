<?php

namespace Langfuse\Event;

class SpanEvent extends IngestionEvent
{
    public function __construct(string $id, string $timestamp, array $body)
    {
        parent::__construct('span-create', $id, $timestamp, $body);
    }
}
