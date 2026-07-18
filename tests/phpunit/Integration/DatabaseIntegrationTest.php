<?php

/**
 * Integration Tests for Database Models
 * 
 * These tests run against a real MariaDB instance (provided by 
 * docker-compose or GitHub Actions service containers).
 * They validate the full DB connection, schema integrity, and
 * model query execution.
 */

namespace Tests\Integration;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DatabaseIntegrationTest extends TestCase
{
    private static ?\mysqli $conn = null;

    public static function setUpBeforeClass(): void
    {
        $host     = getenv('MYSQL_HOSTNAME') ?: '127.0.0.1';
        $user     = getenv('MYSQL_USER') ?: 'test_user';
        $password = getenv('MYSQL_PASSWORD') ?: 'test_password';
        $dbname   = getenv('MYSQL_DATABASE') ?: 'basketball_store';

        $maxRetries = 15;
        $retry = 0;

        while ($retry < $maxRetries) {
            try {
                self::$conn = new \mysqli($host, $user, $password, $dbname);
                if (!self::$conn->connect_error) {
                    self::$conn->set_charset('utf8mb4');
                    return;
                }
            } catch (\mysqli_sql_exception $e) {
                // Retry
            }
            $retry++;
            sleep(2);
        }

        self::markTestSkipped('Could not connect to test database after ' . $maxRetries . ' retries.');
    }

    public static function tearDownAfterClass(): void
    {
        if (self::$conn) {
            self::$conn->close();
            self::$conn = null;
        }
    }

    // ─── Connection Tests ───────────────────────────────────────────

    public function testDatabaseConnectionIsActive(): void
    {
        $this->assertNotNull(self::$conn, 'Database connection should be established');
        $this->assertTrue(self::$conn->ping(), 'Database connection should be alive');
    }

    // ─── Schema Integrity Tests ─────────────────────────────────────

    #[DataProvider('requiredTablesProvider')]
    public function testRequiredTableExists(string $tableName): void
    {
        $result = self::$conn->query("SHOW TABLES LIKE '$tableName'");
        $this->assertGreaterThan(0, $result->num_rows, "Table '$tableName' should exist");
    }

    public static function requiredTablesProvider(): array
    {
        return [
            'authorization table' => ['authorization'],
            'banner table'        => ['banner'],
            'bill table'          => ['bill'],
            'cart table'          => ['cart'],
            'category table'      => ['category'],
            'product table'       => ['product'],
            'product_type table'  => ['product_type'],
            'promotion table'     => ['promotion'],
            'typical_products'    => ['typical_products'],
            'user table'          => ['user'],
            'product_reviews'     => ['product_reviews'],
        ];
    }

    // ─── Column Integrity Tests ─────────────────────────────────────

    public function testUserTableHasRequiredColumns(): void
    {
        $result = self::$conn->query("DESCRIBE user");
        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'];
        }

        $required = ['id_user', 'id_auth', 'last_name', 'first_name', 'phone', 'gender',
                      'email', 'address', 'username', 'password', 'status', 'reset_token'];

        foreach ($required as $col) {
            $this->assertContains($col, $columns, "User table should have column '$col'");
        }
    }

    public function testProductTableHasRequiredColumns(): void
    {
        $result = self::$conn->query("DESCRIBE product");
        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'];
        }

        $required = ['id_product', 'title_product', 'name_product', 'price', 'quantity',
                      'id_category', 'id_product_type', 'main_image', 'id_promotion',
                      'status', 'description'];

        foreach ($required as $col) {
            $this->assertContains($col, $columns, "Product table should have column '$col'");
        }
    }

    // ─── Seed Data Tests ────────────────────────────────────────────

    public function testAuthorizationRolesExist(): void
    {
        $result = self::$conn->query("SELECT COUNT(*) as cnt FROM authorization");
        $row = $result->fetch_assoc();
        $this->assertGreaterThanOrEqual(3, (int) $row['cnt'], 'Should have at least 3 auth roles');
    }

    public function testCategoriesExist(): void
    {
        $result = self::$conn->query("SELECT COUNT(*) as cnt FROM category");
        $row = $result->fetch_assoc();
        $this->assertGreaterThanOrEqual(3, (int) $row['cnt'], 'Should have at least 3 categories');
    }

    public function testProductTypesExist(): void
    {
        $result = self::$conn->query("SELECT COUNT(*) as cnt FROM product_type");
        $row = $result->fetch_assoc();
        $this->assertGreaterThan(0, (int) $row['cnt'], 'Should have product types seeded');
    }

    public function testProductsExist(): void
    {
        $result = self::$conn->query("SELECT COUNT(*) as cnt FROM product");
        $row = $result->fetch_assoc();
        $this->assertGreaterThan(0, (int) $row['cnt'], 'Should have products seeded');
    }

    public function testPromotionsExist(): void
    {
        $result = self::$conn->query("SELECT COUNT(*) as cnt FROM promotion");
        $row = $result->fetch_assoc();
        $this->assertGreaterThan(0, (int) $row['cnt'], 'Should have promotions seeded');
    }

    public function testUsersExist(): void
    {
        $result = self::$conn->query("SELECT COUNT(*) as cnt FROM user");
        $row = $result->fetch_assoc();
        $this->assertGreaterThan(0, (int) $row['cnt'], 'Should have users seeded');
    }

    // ─── Query Tests (simulate model queries) ──────────────────────

    public function testHomePageQueryReturnsProducts(): void
    {
        $query = "SELECT p.*, 
                    prom.value as d_price,
                    prom.type_sale as type_p,
                    prom.type_promotion as name_sale,
                    pt.name_pt as p_type_name
                FROM product p
                LEFT JOIN promotion prom ON p.id_promotion = prom.id_promotion
                LEFT JOIN product_type pt ON p.id_product_type = pt.id_product_type
                WHERE p.id_category = 1 AND p.status >= 1 
                ORDER BY p.timestamp DESC 
                LIMIT 0, 8";

        $result = self::$conn->query($query);
        $this->assertNotFalse($result, 'Home page product query should execute successfully');
        $this->assertGreaterThan(0, $result->num_rows, 'Should return products for shoes category');
    }

    public function testBannerQueryReturnsResults(): void
    {
        $query = "SELECT * FROM banner WHERE status = 1 ORDER BY timestamp DESC";
        $result = self::$conn->query($query);
        $this->assertNotFalse($result, 'Banner query should execute successfully');
        $this->assertGreaterThan(0, $result->num_rows, 'Should return active banners');
    }

    public function testProductTypeQueryReturnsResults(): void
    {
        $query = "SELECT id_product_type, name_pt FROM product_type WHERE id_category = 1";
        $result = self::$conn->query($query);
        $this->assertNotFalse($result, 'Product type query should execute successfully');
        $this->assertGreaterThan(0, $result->num_rows, 'Should return product types for category 1');
    }

    public function testUserLoginQuery(): void
    {
        // Use the known admin credentials from seed data
        $username = 'admin';
        $password = 'fcea920f7412b5da7be0cf42b8c93759'; // md5('1234567')
        
        $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
        $result = self::$conn->query($query);
        $this->assertNotFalse($result, 'Login query should execute successfully');
        $this->assertEquals(1, $result->num_rows, 'Should find the admin user');
    }

    // ─── Foreign Key Constraint Tests ───────────────────────────────

    public function testProductForeignKeysAreValid(): void
    {
        // Check that all products reference valid categories
        $query = "SELECT p.id_product FROM product p 
                  LEFT JOIN category c ON p.id_category = c.id_category 
                  WHERE c.id_category IS NULL";
        $result = self::$conn->query($query);
        $this->assertEquals(0, $result->num_rows, 'All products should have valid category references');
    }

    public function testProductPromotionForeignKeysAreValid(): void
    {
        $query = "SELECT p.id_product FROM product p 
                  LEFT JOIN promotion prom ON p.id_promotion = prom.id_promotion 
                  WHERE prom.id_promotion IS NULL";
        $result = self::$conn->query($query);
        $this->assertEquals(0, $result->num_rows, 'All products should have valid promotion references');
    }
}
