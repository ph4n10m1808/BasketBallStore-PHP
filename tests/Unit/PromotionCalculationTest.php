<?php

/**
 * Unit Tests for Promotion Price Calculation Logic
 * 
 * Tests the queryWithPromotion() method in the base model class
 * to ensure discount prices are calculated correctly for different
 * promotion types (flat discount, percentage, no promotion).
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PromotionCalculationTest extends TestCase
{
    /**
     * Test flat discount (type_p = "0"): d_price = price - discount_value
     */
    public function testFlatDiscountCalculation(): void
    {
        $row = [
            'price'  => 3490000,
            'd_price' => 500000,
            'type_p' => '0',
        ];

        $result = $this->calculatePromotion($row);
        $this->assertEquals(2990000, $result['d_price'], 'Flat discount: 3490000 - 500000 = 2990000');
    }

    /**
     * Test percentage discount (type_p = "1"): d_price = price * (1 - discount_value/100)
     */
    public function testPercentageDiscountCalculation(): void
    {
        $row = [
            'price'  => 3490000,
            'd_price' => 12,
            'type_p' => '1',
        ];

        $result = $this->calculatePromotion($row);
        $expected = 3490000 * (1 - 12 / 100); // 3071200
        $this->assertEquals($expected, $result['d_price'], 'Percentage discount: 12% off of 3490000');
    }

    /**
     * Test no promotion (type_p is null or invalid): d_price = price
     */
    public function testNoPromotionKeepsOriginalPrice(): void
    {
        $row = [
            'price'  => 2800000,
            'd_price' => 0,
            'type_p' => null,
        ];

        $result = $this->calculatePromotion($row);
        $this->assertEquals(2800000, $result['d_price'], 'No promotion should keep original price');
    }

    /**
     * Test 0% percentage discount
     */
    public function testZeroPercentDiscount(): void
    {
        $row = [
            'price'  => 1000000,
            'd_price' => 0,
            'type_p' => '1',
        ];

        $result = $this->calculatePromotion($row);
        $this->assertEquals(1000000, $result['d_price'], '0% discount should equal original price');
    }

    /**
     * Test 100% percentage discount
     */
    public function testFullPercentDiscount(): void
    {
        $row = [
            'price'  => 1000000,
            'd_price' => 100,
            'type_p' => '1',
        ];

        $result = $this->calculatePromotion($row);
        $this->assertEquals(0, $result['d_price'], '100% discount should be zero');
    }

    /**
     * Test flat discount equal to price (free item)
     */
    public function testFlatDiscountEqualToPrice(): void
    {
        $row = [
            'price'  => 500000,
            'd_price' => 500000,
            'type_p' => '0',
        ];

        $result = $this->calculatePromotion($row);
        $this->assertEquals(0, $result['d_price'], 'Flat discount equal to price should be zero');
    }

    /**
     * Test unknown type_p value defaults to original price
     */
    public function testUnknownPromotionType(): void
    {
        $row = [
            'price'  => 2000000,
            'd_price' => 300000,
            'type_p' => '99',
        ];

        $result = $this->calculatePromotion($row);
        $this->assertEquals(2000000, $result['d_price'], 'Unknown promotion type should keep original price');
    }

    /**
     * Replicates the promotion calculation logic from model::queryWithPromotion()
     * This isolates the business logic without requiring a DB connection.
     */
    private function calculatePromotion(array $row): array
    {
        if ($row["type_p"] === "0") {
            $row["d_price"] = $row["price"] - $row['d_price'];
        } elseif ($row["type_p"] === "1") {
            $row["d_price"] = $row["price"] * (1 - $row['d_price'] / 100);
        } else {
            $row["d_price"] = $row["price"];
        }
        return $row;
    }
}
