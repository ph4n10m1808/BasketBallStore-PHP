<?php

final class RequestTelemetry
{
    private const MAX_VALUE_LENGTH = 2048;

    private static float $startedAt;
    private static string $requestId;
    private static array $server;
    private static array $query;
    private static array $body;
    private static bool $captureValues;
    private static bool $started = false;

    public static function start(array $server, array $query, array $body): void
    {
        if (self::$started || getenv('APP_TELEMETRY') === '0') {
            return;
        }

        self::$started = true;
        self::$startedAt = microtime(true);
        self::$requestId = self::requestId($server['HTTP_X_REQUEST_ID'] ?? null);
        self::$server = $server;
        self::$query = $query;
        self::$body = $body;
        self::$captureValues = getenv('APP_LOG_INPUT_VALUES') !== '0';

        if (!headers_sent()) {
            header('X-Request-ID: ' . self::$requestId);
        }

        register_shutdown_function(static function (): void {
            self::finish();
        });
    }

    public static function buildRecord(
        array $server,
        array $query,
        array $body,
        float $startedAt,
        string $requestId,
        int $status,
        int $peakMemory,
        bool $captureValues = true
    ): array {
        $script = basename((string) ($server['SCRIPT_NAME'] ?? 'index.php'));
        $route = $script === 'admin.php'
            ? (string) ($query['mod'] ?? 'dashboard')
            : (string) ($query['page'] ?? 'home');

        return [
            'timestamp' => gmdate('c'),
            'event' => 'request.completed',
            'request_id' => $requestId,
            'method' => (string) ($server['REQUEST_METHOD'] ?? 'GET'),
            'entrypoint' => $script,
            'route' => $route,
            'action' => (string) ($query['act'] ?? ''),
            'status' => $status,
            'duration_ms' => round((microtime(true) - $startedAt) * 1000, 2),
            'peak_memory_bytes' => $peakMemory,
            'remote_addr' => (string) ($server['REMOTE_ADDR'] ?? ''),
            'user_agent' => self::truncate((string) ($server['HTTP_USER_AGENT'] ?? '')),
            'query' => self::sanitize($query, $captureValues),
            'body' => self::sanitize($body, $captureValues),
        ];
    }

    private static function finish(): void
    {
        $record = self::buildRecord(
            self::$server,
            self::$query,
            self::$body,
            self::$startedAt,
            self::$requestId,
            http_response_code(),
            memory_get_peak_usage(true),
            self::$captureValues
        );

        $lastError = error_get_last();
        if ($lastError !== null && in_array($lastError['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            $record['fatal_error'] = [
                'type' => $lastError['type'],
                'message' => self::truncate($lastError['message']),
                'file' => basename($lastError['file']),
                'line' => $lastError['line'],
            ];
        }

        error_log('[basketball-store] ' . json_encode(
            $record,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
        ));
    }

    private static function requestId(mixed $candidate): string
    {
        if (is_string($candidate) && preg_match('/^[A-Za-z0-9._-]{8,128}$/', $candidate) === 1) {
            return $candidate;
        }

        return bin2hex(random_bytes(16));
    }

    private static function sanitize(array $input, bool $captureValues): array
    {
        $sanitized = [];
        foreach ($input as $key => $value) {
            $name = (string) $key;
            if (preg_match('/password|passwd|token|secret|authorization|cookie/i', $name) === 1) {
                $sanitized[$name] = '[REDACTED]';
                continue;
            }

            if (!$captureValues) {
                $sanitized[$name] = '[CAPTURE_DISABLED]';
            } elseif (is_array($value)) {
                $sanitized[$name] = self::sanitize($value, true);
            } elseif (is_scalar($value) || $value === null) {
                $sanitized[$name] = self::truncate((string) $value);
            } else {
                $sanitized[$name] = '[' . get_debug_type($value) . ']';
            }
        }

        return $sanitized;
    }

    private static function truncate(string $value): string
    {
        if (strlen($value) <= self::MAX_VALUE_LENGTH) {
            return $value;
        }

        return substr($value, 0, self::MAX_VALUE_LENGTH) . '[TRUNCATED]';
    }
}
