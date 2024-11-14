<?php

namespace Langfuse;

class LangfuseManager {
    private $client;

    public function __construct(string $publicKey, string $secretKey) {
        $this->client = new LangfuseClient($publicKey, $secretKey);
    }

    public function startTrace(string $name, array $metadata = []): Trace {
        $trace = new Trace($name, $metadata);
        $this->client->post('/trace', $trace->toArray());

        return $trace;
    }

    public function endTrace(Trace $trace): void {
        $this->client->post('/trace/' . $trace->getTraceId() . '/end', []);
    }

    public function addLog(Trace $trace, string $message): void {
        $data = [
            'traceId' => $trace->getTraceId(),
            'message' => $message
        ];
        $this->client->post('/log', $data);
    }
}
