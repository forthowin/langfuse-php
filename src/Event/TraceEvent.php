<?php

namespace Langfuse\Event;

class TraceEvent extends IngestionEvent
{
    public function __construct(string $id, string $timestamp, array $body)
    {
        parent::__construct('trace-create', $id, $timestamp, $body);
    }
}
