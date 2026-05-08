<?php

/**
 * Integration Test for Docker Build
 * 
 * Validates the Docker build configuration by checking that
 * all required files exist in the correct locations, and 
 * PHP syntax is valid across the entire codebase.
 */

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;

class DockerBuildTest extends TestCase
{
    // ─── Required File Existence ────────────────────────────────────

    /**
     * @dataProvider requiredFilesProvider
     */
    public function testRequiredFileExists(string $filePath): void
    {
        $fullPath = __DIR__ . '/../../../' . $filePath;
        $this->assertFileExists($fullPath, "Required file '$filePath' should exist");
    }

    public static function requiredFilesProvider(): array
    {
        return [
            'Dockerfile'              => ['src/web/Dockerfile'],
            'docker-compose.yml'      => ['docker-compose.yml'],
            'docker-entrypoint.sh'    => ['src/web/docker-entrypoint.sh'],
            '.env.example'            => ['.env.example'],
            'db/Dockerfile'           => ['src/db/Dockerfile'],
            'db/dbstore.sql'          => ['src/db/dbstore.sql'],
            'public/index.php'        => ['src/web/public/index.php'],
        ];
    }

    // ─── PHP Syntax Validation ──────────────────────────────────────

    /**
     * @dataProvider phpFilesProvider
     */
    public function testPhpFileSyntax(string $filePath): void
    {
        $fullPath = __DIR__ . '/../../../' . $filePath;
        if (!file_exists($fullPath)) {
            $this->markTestSkipped("File '$filePath' not found");
        }

        $output = [];
        $returnCode = 0;
        exec("php -l " . escapeshellarg($fullPath) . " 2>&1", $output, $returnCode);
        $this->assertEquals(0, $returnCode, "PHP syntax error in '$filePath': " . implode("\n", $output));
    }

    public static function phpFilesProvider(): array
    {
        $files = [];
        $files = [];
        $appDir = __DIR__ . '/../../../src/web/app';
        $adminDir = __DIR__ . '/../../../src/web/admin';
        $publicDir = __DIR__ . '/../../../src/web/public';
        
        $dirs = [$appDir, $adminDir, $publicDir];
        
        foreach ($dirs as $dir) {
            if (!is_dir($dir)) continue;
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $relativePath = str_replace(dirname(__DIR__, 2) . '/', '', $file->getPathname());
                    $files[basename($file->getPathname())] = [$relativePath];
                }
            }
        }

        return $files;
    }

    // ─── Dockerfile Validation ──────────────────────────────────────

    public function testDockerfileHasCorrectBaseImage(): void
    {
        $content = file_get_contents(__DIR__ . '/../../../src/web/Dockerfile');
        $this->assertStringContainsString('php:', $content, 'Dockerfile should use PHP base image');
        $this->assertStringContainsString('apache', $content, 'Dockerfile should use Apache variant');
    }

    public function testDockerfileInstallsMysqli(): void
    {
        $content = file_get_contents(__DIR__ . '/../../../src/web/Dockerfile');
        $this->assertStringContainsString('mysqli', $content, 'Dockerfile should install mysqli extension');
    }

    public function testDockerfileInstallsGD(): void
    {
        $content = file_get_contents(__DIR__ . '/../../../src/web/Dockerfile');
        $this->assertStringContainsString('gd', $content, 'Dockerfile should install GD extension');
    }

    public function testDockerfileEnablesModRewrite(): void
    {
        $content = file_get_contents(__DIR__ . '/../../../src/web/Dockerfile');
        $this->assertStringContainsString('rewrite', $content, 'Dockerfile should enable mod_rewrite');
    }

    public function testDockerComposeHasRequiredServices(): void
    {
        $content = file_get_contents(__DIR__ . '/../../../docker-compose.yml');
        $this->assertStringContainsString('db:', $content, 'docker-compose should define db service');
        $this->assertStringContainsString('web:', $content, 'docker-compose should define web service');
    }

    public function testDbDockerfileUsesMariaDB(): void
    {
        $content = file_get_contents(__DIR__ . '/../../../src/db/Dockerfile');
        $this->assertStringContainsString('mariadb', $content, 'DB Dockerfile should use MariaDB');
    }

    // ─── SQL Schema Validation ──────────────────────────────────────

    public function testSQLFileIsNotEmpty(): void
    {
        $content = file_get_contents(__DIR__ . '/../../../src/db/dbstore.sql');
        $this->assertNotEmpty($content, 'dbstore.sql should not be empty');
        $this->assertGreaterThan(1000, strlen($content), 'dbstore.sql should have substantial content');
    }

    public function testSQLFileCreatesDatabase(): void
    {
        $content = file_get_contents(__DIR__ . '/../../../src/db/dbstore.sql');
        $this->assertStringContainsString('basketball_store', $content, 'SQL should create basketball_store database');
    }

    // ─── Entrypoint Script Validation ───────────────────────────────

    public function testEntrypointIsExecutable(): void
    {
        $path = __DIR__ . '/../../../src/web/docker-entrypoint.sh';
        $content = file_get_contents($path);
        $this->assertStringStartsWith('#!/bin/bash', $content, 'Entrypoint should have bash shebang');
        $this->assertStringContainsString('set -e', $content, 'Entrypoint should use set -e');
    }

    public function testEnvExampleHasRequiredVariables(): void
    {
        $content = file_get_contents(__DIR__ . '/../../../.env.example');
        $required = ['MYSQL_ROOT_PASSWORD', 'MYSQL_DATABASE', 'MYSQL_USER', 'MYSQL_PASSWORD', 'MYSQL_HOSTNAME'];
        
        foreach ($required as $var) {
            $this->assertStringContainsString($var, $content, ".env.example should define $var");
        }
    }
}
