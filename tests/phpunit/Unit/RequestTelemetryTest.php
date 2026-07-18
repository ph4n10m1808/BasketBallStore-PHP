<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 3) . '/src/web/app/Support/RequestTelemetry.php';

final class RequestTelemetryTest extends TestCase
{
    public function testBuildRecordKeepsLabInputAndRedactsSecrets(): void
    {
        $record = \RequestTelemetry::buildRecord(
            [
                'SCRIPT_NAME' => '/index.php',
                'REQUEST_METHOD' => 'POST',
                'REMOTE_ADDR' => '127.0.0.1',
                'HTTP_USER_AGENT' => 'lab-client',
            ],
            ['page' => 'include', 'file' => '../example.php'],
            ['username' => "demo'", 'password' => 'do-not-log'],
            microtime(true),
            'request-12345678',
            200,
            4096
        );

        $this->assertSame('include', $record['route']);
        $this->assertSame('../example.php', $record['query']['file']);
        $this->assertSame("demo'", $record['body']['username']);
        $this->assertSame('[REDACTED]', $record['body']['password']);
        $this->assertSame(200, $record['status']);
    }

    public function testBuildRecordCanCaptureOnlyParameterNames(): void
    {
        $record = \RequestTelemetry::buildRecord(
            ['SCRIPT_NAME' => '/admin.php'],
            ['mod' => 'product', 'act' => 'export'],
            ['format' => 'csv'],
            microtime(true),
            'request-12345678',
            500,
            8192,
            false
        );

        $this->assertSame('product', $record['route']);
        $this->assertSame('export', $record['action']);
        $this->assertSame('[CAPTURE_DISABLED]', $record['body']['format']);
    }
}
