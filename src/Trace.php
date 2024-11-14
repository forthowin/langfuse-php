<?php

namespace Langfuse;

class Trace {
    private $traceId;
    private $name;
    private $metadata;

    public function __construct(string $name, array $metadata = []) {
        $this->traceId = uniqid('', true);
        $this->name = $name;
        $this->metadata = $metadata;
    }

    public function toArray(): array {
        return [
            'traceId' => $this->traceId,
            'name' => $this->name,
            'metadata' => $this->metadata
        ];
    }

    public function getTraceId(): string {
        return $this->traceId;
    }
}
