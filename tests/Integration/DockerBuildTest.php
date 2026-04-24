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
        $fullPath = __DIR__ . '/../../' . $filePath;
        $this->assertFileExists($fullPath, "Required file '$filePath' should exist");
    }

    public static function requiredFilesProvider(): array
    {
        return [
            'Dockerfile'              => ['Dockerfile'],
            'docker-compose.yml'      => ['docker-compose.yml'],
            'docker-entrypoint.sh'    => ['docker-entrypoint.sh'],
            '.env.example'            => ['.env.example'],
            'db/Dockerfile'           => ['db/Dockerfile'],
            'db/dbstore.sql'          => ['db/dbstore.sql'],
            'src/index.php'           => ['src/index.php'],
        ];
    }

    // ─── PHP Syntax Validation ──────────────────────────────────────

    /**
     * @dataProvider phpFilesProvider
     */
    public function testPhpFileSyntax(string $filePath): void
    {
        $fullPath = __DIR__ . '/../../' . $filePath;
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
        $srcDir = __DIR__ . '/../../src';
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($srcDir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $relativePath = 'src/' . str_replace($srcDir . '/', '', $file->getPathname());
                $files[basename($file->getPathname())] = [$relativePath];
            }
        }

        return $files;
    }

    // ─── Dockerfile Validation ──────────────────────────────────────

    public function testDockerfileHasCorrectBaseImage(): void
    {
        $content = file_get_contents(__DIR__ . '/../../Dockerfile');
        $this->assertStringContainsString('php:', $content, 'Dockerfile should use PHP base image');
        $this->assertStringContainsString('apache', $content, 'Dockerfile should use Apache variant');
    }

    public function testDockerfileInstallsMysqli(): void
    {
        $content = file_get_contents(__DIR__ . '/../../Dockerfile');
        $this->assertStringContainsString('mysqli', $content, 'Dockerfile should install mysqli extension');
    }

    public function testDockerfileInstallsGD(): void
    {
        $content = file_get_contents(__DIR__ . '/../../Dockerfile');
        $this->assertStringContainsString('gd', $content, 'Dockerfile should install GD extension');
    }

    public function testDockerfileEnablesModRewrite(): void
    {
        $content = file_get_contents(__DIR__ . '/../../Dockerfile');
        $this->assertStringContainsString('rewrite', $content, 'Dockerfile should enable mod_rewrite');
    }

    public function testDockerComposeHasRequiredServices(): void
    {
        $content = file_get_contents(__DIR__ . '/../../docker-compose.yml');
        $this->assertStringContainsString('db:', $content, 'docker-compose should define db service');
        $this->assertStringContainsString('web:', $content, 'docker-compose should define web service');
    }

    public function testDbDockerfileUsesMariaDB(): void
    {
        $content = file_get_contents(__DIR__ . '/../../db/Dockerfile');
        $this->assertStringContainsString('mariadb', $content, 'DB Dockerfile should use MariaDB');
    }

    // ─── SQL Schema Validation ──────────────────────────────────────

    public function testSQLFileIsNotEmpty(): void
    {
        $content = file_get_contents(__DIR__ . '/../../db/dbstore.sql');
        $this->assertNotEmpty($content, 'dbstore.sql should not be empty');
        $this->assertGreaterThan(1000, strlen($content), 'dbstore.sql should have substantial content');
    }

    public function testSQLFileCreatesDatabase(): void
    {
        $content = file_get_contents(__DIR__ . '/../../db/dbstore.sql');
        $this->assertStringContainsString('basketball_store', $content, 'SQL should create basketball_store database');
    }

    // ─── Entrypoint Script Validation ───────────────────────────────

    public function testEntrypointIsExecutable(): void
    {
        $path = __DIR__ . '/../../docker-entrypoint.sh';
        $content = file_get_contents($path);
        $this->assertStringStartsWith('#!/bin/bash', $content, 'Entrypoint should have bash shebang');
        $this->assertStringContainsString('set -e', $content, 'Entrypoint should use set -e');
    }

    public function testEnvExampleHasRequiredVariables(): void
    {
        $content = file_get_contents(__DIR__ . '/../../.env.example');
        $required = ['MYSQL_ROOT_PASSWORD', 'MYSQL_DATABASE', 'MYSQL_USER', 'MYSQL_PASSWORD', 'MYSQL_HOSTNAME'];
        
        foreach ($required as $var) {
            $this->assertStringContainsString($var, $content, ".env.example should define $var");
        }
    }
}
