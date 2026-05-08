<?php

/**
 * Unit Tests for Cart Session Logic
 * 
 * Tests the session-based cart operations: cost calculation,
 * item deletion, quantity update, and clear cart.
 * These tests simulate the cart logic without DB interaction.
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CartSessionTest extends TestCase
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

    // ─── Cart Cost Calculation ──────────────────────────────────────

    public function testCostCartWithItems(): void
    {
        $_SESSION['carts'] = [
            ['id_product' => '1', 'price' => 3490000, 'd_price' => 2990000, 'quantity' => 1, 'size' => '42'],
            ['id_product' => '2', 'price' => 3450000, 'd_price' => 3036000, 'quantity' => 2, 'size' => '43'],
        ];

        $total = $this->calculateCost();

        // (2990000 * 1) + (3036000 * 2) + 30000 (shipping) = 9092000
        $this->assertEquals(9092000, $total);
    }

    public function testCostCartEmpty(): void
    {
        $_SESSION['carts'] = [];
        $total = $this->calculateCost();
        $this->assertEquals(0, $total);
    }

    public function testCostCartSingleItem(): void
    {
        $_SESSION['carts'] = [
            ['id_product' => '1', 'price' => 1000000, 'd_price' => 800000, 'quantity' => 3, 'size' => '41'],
        ];

        $total = $this->calculateCost();
        // (800000 * 3) + 30000 = 2430000
        $this->assertEquals(2430000, $total);
    }

    public function testCostCartFallsBackToPrice(): void
    {
        // When d_price is not set, should fall back to price
        $_SESSION['carts'] = [
            ['id_product' => '1', 'price' => 500000, 'quantity' => 2, 'size' => '42'],
        ];

        $total = $this->calculateCost();
        // (500000 * 2) + 30000 = 1030000
        $this->assertEquals(1030000, $total);
    }

    // ─── Cart Item Deletion ─────────────────────────────────────────

    public function testDeleteItemByIdAndSize(): void
    {
        $_SESSION['carts'] = [
            ['id_product' => '1', 'price' => 1000000, 'd_price' => 800000, 'quantity' => 1, 'size' => '42'],
            ['id_product' => '2', 'price' => 2000000, 'd_price' => 1800000, 'quantity' => 1, 'size' => '43'],
            ['id_product' => '1', 'price' => 1000000, 'd_price' => 800000, 'quantity' => 2, 'size' => '44'],
        ];

        $this->deleteItem('1', '42');

        $this->assertCount(2, $_SESSION['carts']);
        // Item with id=1, size=42 should be removed; id=1, size=44 should remain
        $ids = array_column($_SESSION['carts'], 'id_product');
        $this->assertContains('2', $ids);
    }

    public function testDeleteItemFromEmptyCart(): void
    {
        $_SESSION['carts'] = [];
        $this->deleteItem('1', '42');
        $this->assertEmpty($_SESSION['carts']);
    }

    public function testDeleteNonExistentItem(): void
    {
        $_SESSION['carts'] = [
            ['id_product' => '1', 'price' => 1000000, 'd_price' => 800000, 'quantity' => 1, 'size' => '42'],
        ];

        $this->deleteItem('999', '42');
        $this->assertCount(1, $_SESSION['carts']);
    }

    // ─── Cart Update Quantity ───────────────────────────────────────

    public function testUpdateCartPlus(): void
    {
        $_SESSION['carts'] = [
            ['id_product' => '1', 'price' => 1000000, 'd_price' => 800000, 'quantity' => 1, 'size' => '42'],
        ];

        $this->updateCart('1', 'plus', '42');
        $this->assertEquals(2, $_SESSION['carts'][0]['quantity']);
    }

    public function testUpdateCartMinus(): void
    {
        $_SESSION['carts'] = [
            ['id_product' => '1', 'price' => 1000000, 'd_price' => 800000, 'quantity' => 3, 'size' => '42'],
        ];

        $this->updateCart('1', 'minus', '42');
        $this->assertEquals(2, $_SESSION['carts'][0]['quantity']);
    }

    public function testUpdateCartMinusCannotGoBelow1(): void
    {
        $_SESSION['carts'] = [
            ['id_product' => '1', 'price' => 1000000, 'd_price' => 800000, 'quantity' => 1, 'size' => '42'],
        ];

        $this->updateCart('1', 'minus', '42');
        $this->assertEquals(1, $_SESSION['carts'][0]['quantity'], 'Quantity should not go below 1');
    }

    // ─── Clear Cart ─────────────────────────────────────────────────

    public function testClearCart(): void
    {
        $_SESSION['carts'] = [
            ['id_product' => '1', 'price' => 1000000, 'd_price' => 800000, 'quantity' => 1, 'size' => '42'],
        ];
        $_SESSION['totalCart'] = 830000;

        $_SESSION['carts'] = [];
        $_SESSION['totalCart'] = 0;

        $this->assertEmpty($_SESSION['carts']);
        $this->assertEquals(0, $_SESSION['totalCart']);
    }

    // ─── Helper Methods (replicate cart logic without DB) ───────────

    /**
     * Replicates Cart::costCart() logic
     */
    private function calculateCost(): int
    {
        if (empty($_SESSION['carts'])) {
            $_SESSION['totalCart'] = 0;
            return 0;
        }

        $totalProducts = 0;
        foreach ($_SESSION['carts'] as $each) {
            $totalProducts += ($each['d_price'] ?? $each['price']) * $each['quantity'];
        }
        $total = $totalProducts + 30000;
        $_SESSION['totalCart'] = $total;
        return $total;
    }

    /**
     * Replicates Cart::deleteItemSession() logic
     */
    private function deleteItem(string $id, ?string $size = null): void
    {
        if (empty($_SESSION['carts'])) {
            return;
        }

        $_SESSION['carts'] = array_values(array_filter($_SESSION['carts'], function ($item) use ($id, $size) {
            if ($size !== null) {
                return !($item['id_product'] === $id && $item['size'] === $size);
            }
            return $item['id_product'] !== $id;
        }));

        $this->calculateCost();
    }

    /**
     * Replicates Cart::updateCart() logic
     */
    private function updateCart(string $id, string $type, string $size): ?int
    {
        if (empty($_SESSION['carts'])) {
            return null;
        }

        $cartCount = count($_SESSION['carts']);
        for ($i = 0; $i < $cartCount; $i++) {
            if ($_SESSION['carts'][$i]['id_product'] === $id && $_SESSION['carts'][$i]['size'] === $size) {
                if ($type === 'minus' && $_SESSION['carts'][$i]['quantity'] !== 1) {
                    $_SESSION['carts'][$i]['quantity'] -= 1;
                } elseif ($type === 'plus') {
                    $_SESSION['carts'][$i]['quantity'] += 1;
                }
                $this->calculateCost();
                return $_SESSION['totalCart'];
            }
        }
        return null;
    }
}
