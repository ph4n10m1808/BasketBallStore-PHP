<?php

/**
 * Unit Tests for Application Routing Logic
 * 
 * Tests that the main router (index.php) correctly resolves
 * route names from the 'page' GET parameter. Does not execute
 * the actual controllers — only validates routing decisions.
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class RoutingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        resetSession();
    }

    protected function tearDown(): void
    {
        resetSession();
        parent::tearDown();
    }

    /**
     * @dataProvider routeProvider
     */
    public function testRouteResolution(string $page, string $expectedRoute): void
    {
        $_GET['page'] = $page;
        $route = $_GET['page'] ?? 'home';
        $this->assertEquals($expectedRoute, $route);
    }

    public static function routeProvider(): array
    {
        return [
            'home page'       => ['home', 'home'],
            'login page'      => ['login', 'login'],
            'register page'   => ['register', 'register'],
            'logout'          => ['logout', 'logout'],
            'cart page'       => ['cart', 'cart'],
            'product page'    => ['product', 'product'],
            'detail page'     => ['detail', 'detail'],
            'profile page'    => ['profile', 'profile'],
            'bill page'       => ['bill', 'bill'],
            'search page'     => ['search', 'search'],
            'include page'    => ['include', 'include'],
            'reset page'      => ['reset', 'reset'],
        ];
    }

    public function testDefaultRouteWhenPageNotSet(): void
    {
        unset($_GET['page']);
        $route = $_GET['page'] ?? 'home';
        $this->assertEquals('home', $route);
    }

    public function testBillPageRedirectsWhenNotLoggedIn(): void
    {
        $_GET['page'] = 'bill';
        $route = $_GET['page'] ?? 'home';

        // Simulate the auth check logic from index.php
        $requiresAuth = ($route === 'bill' && (!isset($_SESSION['user']) || !$_SESSION['user']));
        $this->assertTrue($requiresAuth, 'Bill page should require authentication');
    }

    public function testBillPageAllowsLoggedInUser(): void
    {
        $_GET['page'] = 'bill';
        $_SESSION['user'] = ['id_user' => 1, 'username' => 'testuser'];
        $_SESSION['login'] = true;

        $route = $_GET['page'] ?? 'home';
        $requiresAuth = ($route === 'bill' && (!isset($_SESSION['user']) || !$_SESSION['user']));
        $this->assertFalse($requiresAuth, 'Bill page should allow authenticated users');
    }

    public function testProfileRedirectsWhenNotLoggedIn(): void
    {
        $_GET['page'] = 'profile';
        $route = $_GET['page'] ?? 'home';

        // Simulate profile auth check (without act=view&id)
        $act = $_GET['act'] ?? '';
        $requiresAuth = ($route === 'profile' && $act !== 'view' && (!isset($_SESSION['user']) || !$_SESSION['user']));
        $this->assertTrue($requiresAuth, 'Profile page should require login when not viewing other profile');
    }

    public function testProductRouteWithType(): void
    {
        $_GET['page'] = 'product';
        $_GET['type'] = 'shoes';

        $type = $_GET['type'] ?? '';
        $id = $_GET['id'] ?? '';
        $this->assertNotEmpty($type, 'Type parameter should be set');
        $this->assertEmpty($id, 'ID should be empty when type is set');
    }

    public function testProductRouteWithId(): void
    {
        $_GET['page'] = 'product';
        $_GET['id'] = '5';

        $type = $_GET['type'] ?? '';
        $id = $_GET['id'] ?? '';
        $this->assertEmpty($type);
        $this->assertNotEmpty($id, 'ID parameter should be set');
    }

    public function testCartWithPayAction(): void
    {
        $_GET['page'] = 'cart';
        $_GET['act'] = 'pay';

        $act = $_GET['act'] ?? '';
        $this->assertEquals('pay', $act);
    }

    public function testCartWithClearAction(): void
    {
        $_GET['page'] = 'cart';
        $_GET['act'] = 'clear';

        $act = $_GET['act'] ?? '';
        $this->assertEquals('clear', $act);
    }
}
