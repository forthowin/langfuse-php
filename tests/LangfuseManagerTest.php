<?php

use Langfuse\LangfuseManager;
use Langfuse\LangfuseClient;
use GuzzleHttp\Client;
use Langfuse\Trace;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class LangfuseManagerTest extends TestCase
{
    public function testTraceIsCreated(): void
    {
        // Arrange
        /** @var Client|MockObject $mockClient */
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())
            ->method('post');

        $langFuseClient = new LangfuseClient('api_key', $mockClient);

        $sut = new LangfuseManager('api_key');
        $reflection = new \ReflectionClass($sut);
        $property = $reflection->getProperty('client');
        $property->setValue($sut, $langFuseClient);

        // Act
        $trace = $sut->startTrace('test_trace');

        // Assert
        $this->assertNotNull($trace);
        $this->assertNotNull($trace->getTraceId());
        $this->assertEquals(
            'test_trace',
            $trace->toArray()['name']);
    }

    public function testTraceIsEnded(): void
    {
        // Arrange
        /** @var Client|MockObject $mockClient */
        $mockClient = $this->createMock(Client::class);
        $langFuseClient = new LangfuseClient('api_key', $mockClient);

        $sut = new LangfuseManager('api_key');
        $reflection = new \ReflectionClass($sut);
        $property = $reflection->getProperty('client');
        $property->setValue($sut, $langFuseClient);
        $trace = new Trace('test_trace');

        // Assert
        $mockClient->expects($this->once())
            ->method('post')
        ->with(
            '/trace/' . $trace->getTraceId() . '/end',
            ['json' => []]
        );

        // Act
        $sut->endTrace($trace);
    }

    public function testAddLogToTrace(): void
    {
        // Arrange
        /** @var Client|MockObject $mockClient */
        $mockClient = $this->createMock(Client::class);
        $langFuseClient = new LangfuseClient('api_key', $mockClient);

        $sut = new LangfuseManager('api_key');
        $reflection = new \ReflectionClass($sut);
        $property = $reflection->getProperty('client');
        $property->setValue($sut, $langFuseClient);
        $trace = new Trace('test_trace');

        // Assert
        $mockClient->expects($this->once())
            ->method('post')
            ->with(
                '/log',
                ['json' => ['traceId' => $trace->getTraceId(), 'message' => 'test_message']]
            );

        // Act
        $sut->addLog($trace, 'test_message');
    }
}
