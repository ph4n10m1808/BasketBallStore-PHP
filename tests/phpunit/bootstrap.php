<?php

/**
 * PHPUnit Bootstrap File
 * 
 * Sets up the testing environment for BasketBallStore-PHP.
 * Configures autoloading, mock helpers, and session simulation.
 */

// Keep CLI sessions inside a writable, disposable directory. Some development
// environments mount the system session directory read-only.
$sessionPath = sys_get_temp_dir() . '/basketball-store-phpunit-sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0700, true);
}
session_save_path($sessionPath);

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

// Autoload via Composer. Tests live outside src/web, so resolve from the
// repository root instead of looking for a non-existent tests/vendor folder.
$webRoot = dirname(__DIR__, 2) . '/src/web';
$autoloadPath = $webRoot . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

// The checked-in vendor tree may have been generated before the MVC folders
// were moved. Load the small validation dependency chain deterministically so
// unit tests do not depend on stale generated Composer metadata.
if (!class_exists('Check', false)) {
    require_once $webRoot . '/app/Models/connection.php';
    require_once $webRoot . '/app/Models/model.php';
    require_once $webRoot . '/app/Models/check.php';
}

// Helper: Reset session state between tests
function resetSession(): void
{
    $_SESSION = [];
    $_GET     = [];
    $_POST    = [];
    $_COOKIE  = [];
}
