<?php

/**
 * Unit Tests for the Check (Validation) Model
 * 
 * Tests input validation logic: empty checks, password rules,
 * email format, and phone number format.
 * These tests mock the database connection to isolate validation logic.
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CheckModelTest extends TestCase
{
    private $check;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock Check object that bypasses the DB connection
        $this->check = $this->getMockBuilder(\Check::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['checkUsernameReg'])
            ->getMock();

        // Re-instantiate the methods we want to test with their real implementations
        // For pure validation methods, we use a reflection trick
        $this->check = new class extends \Check {
            public function __construct()
            {
                // Skip parent constructor (which connects to DB)
                // Only override methods that don't need DB
            }
        };
    }

    // ─── checkEmpty Tests ───────────────────────────────────────────

    public function testCheckEmptyWithEmptyString(): void
    {
        $result = $this->check->checkEmpty('');
        $this->assertNotEmpty($result, 'Empty string should return an error message');
        $this->assertEquals('Yêu cầu', $result);
    }

    public function testCheckEmptyWithNull(): void
    {
        $result = $this->check->checkEmpty(null);
        $this->assertNotEmpty($result, 'Null value should return an error message');
    }

    public function testCheckEmptyWithValidValue(): void
    {
        $result = $this->check->checkEmpty('hello');
        $this->assertEmpty($result, 'Non-empty string should return empty');
    }

    public function testCheckEmptyWithZeroString(): void
    {
        $result = $this->check->checkEmpty('0');
        $this->assertEmpty($result, 'String "0" should NOT be considered empty');
    }

    public function testCheckEmptyWithWhitespace(): void
    {
        $result = $this->check->checkEmpty('   ');
        $this->assertEmpty($result, 'Whitespace-only string is not empty by this implementation');
    }

    // ─── checkPassword Tests ────────────────────────────────────────

    public function testCheckPasswordTooShort(): void
    {
        $result = $this->check->checkPassword('abc', 'abc');
        $this->assertNotEmpty($result, 'Password under 6 chars should fail');
        $this->assertStringContainsString('6', $result);
    }

    public function testCheckPasswordMismatch(): void
    {
        $result = $this->check->checkPassword('password123', 'password456');
        $this->assertNotEmpty($result, 'Mismatched passwords should fail');
    }

    public function testCheckPasswordValid(): void
    {
        $result = $this->check->checkPassword('password123', 'password123');
        $this->assertEmpty($result, 'Valid matching passwords should pass');
    }

    public function testCheckPasswordExactlySixChars(): void
    {
        $result = $this->check->checkPassword('abcdef', 'abcdef');
        $this->assertEmpty($result, 'Password with exactly 6 chars should pass');
    }

    public function testCheckPasswordFiveChars(): void
    {
        $result = $this->check->checkPassword('abcde', 'abcde');
        $this->assertNotEmpty($result, 'Password with 5 chars should fail');
    }

    // ─── checkEmailReg Tests ────────────────────────────────────────

    public function testCheckEmailValid(): void
    {
        $result = $this->check->checkEmailReg('user@example.com');
        $this->assertEmpty($result, 'Valid email should pass');
    }

    public function testCheckEmailInvalid(): void
    {
        $result = $this->check->checkEmailReg('not-an-email');
        $this->assertNotEmpty($result, 'Invalid email should fail');
    }

    public function testCheckEmailEmpty(): void
    {
        $result = $this->check->checkEmailReg('');
        $this->assertNotEmpty($result, 'Empty email should fail');
    }

    public function testCheckEmailWithSubdomain(): void
    {
        $result = $this->check->checkEmailReg('user@mail.example.com');
        $this->assertEmpty($result, 'Email with subdomain should pass');
    }

    public function testCheckEmailMissingDomain(): void
    {
        $result = $this->check->checkEmailReg('user@');
        $this->assertNotEmpty($result, 'Email without domain should fail');
    }

    public function testCheckEmailMissingAt(): void
    {
        $result = $this->check->checkEmailReg('userexample.com');
        $this->assertNotEmpty($result, 'Email without @ should fail');
    }

    // ─── checkPhoneReg Tests ────────────────────────────────────────

    public function testCheckPhoneValid(): void
    {
        $result = $this->check->checkPhoneReg('0392859782');
        $this->assertEmpty($result, 'Valid VN phone number should pass');
    }

    public function testCheckPhoneInvalidFormat(): void
    {
        $result = $this->check->checkPhoneReg('12345');
        $this->assertNotEmpty($result, 'Too short phone should fail');
    }

    public function testCheckPhoneNotStartingWithZero(): void
    {
        $result = $this->check->checkPhoneReg('1234567890');
        $this->assertNotEmpty($result, 'Phone not starting with 0 should fail');
    }

    public function testCheckPhoneTooLong(): void
    {
        $result = $this->check->checkPhoneReg('01234567890');
        $this->assertNotEmpty($result, 'Phone with 11 digits should fail');
    }

    public function testCheckPhoneWithLetters(): void
    {
        $result = $this->check->checkPhoneReg('039285abc2');
        $this->assertNotEmpty($result, 'Phone with letters should fail');
    }

    public function testCheckPhoneEmpty(): void
    {
        $result = $this->check->checkPhoneReg('');
        $this->assertNotEmpty($result, 'Empty phone should fail');
    }
}
