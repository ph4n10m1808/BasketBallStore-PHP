<?php

/**
 * PHPUnit Bootstrap File
 * 
 * Sets up the testing environment for BasketBallStore-PHP.
 * Configures autoloading, mock helpers, and session simulation.
 */

// Start session for tests that rely on $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set default environment variables for testing
$envDefaults = [
    'MYSQL_HOSTNAME'      => '127.0.0.1',
    'MYSQL_DATABASE'      => 'basketball_store',
    'MYSQL_USER'          => 'test_user',
    'MYSQL_PASSWORD'      => 'test_password',
    'MYSQL_ROOT_PASSWORD' => 'root_test_password',
];

foreach ($envDefaults as $key => $value) {
    if (!getenv($key)) {
        putenv("$key=$value");
    }
}

// Autoload via Composer
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

// Helper: Reset session state between tests
function resetSession(): void
{
    $_SESSION = [];
    $_GET     = [];
    $_POST    = [];
    $_COOKIE  = [];
}
