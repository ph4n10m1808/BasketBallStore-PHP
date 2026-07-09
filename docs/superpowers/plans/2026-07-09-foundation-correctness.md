# Foundation and Correctness Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make the PHP application predictable on PHP 8.4 and test real cart, promotion, routing, session, and error behavior without removing intentional security-lab behavior.

**Architecture:** Keep the current MVC and query-string router. Extract only deterministic shared behavior into small helpers, then make controllers and models delegate to those helpers while unsafe challenge data paths remain unchanged.

**Tech Stack:** PHP 8.4, mysqli, Apache, PHPUnit 11, Composer

---

### Task 1: Record the baseline and private lab contract

**Files:**
- Create: `docs/private/security-lab-inventory.md` (Git-ignored)
- Create: `tests/baseline/collect.sh`
- Modify: `README.md`

- [ ] **Step 1: Write a baseline collector**

```bash
#!/usr/bin/env bash
set -euo pipefail
mkdir -p /tmp/basketballstore-baseline
find src/web/public/assets -type f -printf '%s\n' |
  awk '{sum += $1; count += 1} END {printf "asset_files=%d\nasset_bytes=%d\n", count, sum}' |
  tee /tmp/basketballstore-baseline/assets.txt
src/web/vendor/bin/phpunit -c src/web/phpunit.xml \
  | tee /tmp/basketballstore-baseline/phpunit.txt
```

- [ ] **Step 2: Run the collector and retain `/tmp/basketballstore-baseline`**

Run: `bash tests/baseline/collect.sh`

Expected: asset counts are printed and PHPUnit exits successfully, or the exact pre-existing failures are recorded before any implementation edit.

- [ ] **Step 3: Create the private inventory**

Use columns `ID | route | weakness | expected behavior | verification | reset | last checked`. Populate it from every existing unsafe sink and challenge middleware found during the audit. Do not add payloads to committed files.

- [ ] **Step 4: Document safe local-lab use**

Add to `README.md`: run only in an isolated Docker network; `docs/private/` contains local maintainer notes and is never published.

- [ ] **Step 5: Commit public baseline tooling**

```bash
git add README.md tests/baseline/collect.sh
git commit -m "test: add modernization baseline collector"
```

### Task 2: Test the real promotion calculator

**Files:**
- Create: `src/web/app/Support/PromotionPrice.php`
- Modify: `src/web/app/Models/model.php`
- Modify: `src/web/app/Models/detailProduct.php`
- Replace: `tests/phpunit/Unit/PromotionCalculationTest.php`

- [ ] **Step 1: Write failing data-provider tests**

Test `PromotionPrice::apply()` for fixed discount, percentage discount, no promotion, a discount larger than price clamped to zero, and percentage outside `0..100` clamped to that range.

```php
#[DataProvider('prices')]
public function testApply(int $price, ?string $type, ?int $value, int $expected): void
{
    self::assertSame($expected, PromotionPrice::apply($price, $type, $value));
}
```

- [ ] **Step 2: Verify the class is missing**

Run: `src/web/vendor/bin/phpunit -c src/web/phpunit.xml tests/phpunit/Unit/PromotionCalculationTest.php`

Expected: FAIL because `PromotionPrice` does not exist.

- [ ] **Step 3: Implement the pure calculator**

```php
final class PromotionPrice
{
    public static function apply(int $price, ?string $type, ?int $value): int
    {
        $value ??= 0;
        return match ($type) {
            '0' => max(0, $price - max(0, $value)),
            '1' => (int) round($price * (1 - min(100, max(0, $value)) / 100)),
            default => $price,
        };
    }
}
```

- [ ] **Step 4: Delegate both model paths to the calculator**

Require the support class from `model.php`; set each row's `d_price` through `PromotionPrice::apply()`. In `DetailProduct::getData()`, use the same call and do not change its intentionally interpolated SQL.

- [ ] **Step 5: Run and commit**

Run: `src/web/vendor/bin/phpunit -c src/web/phpunit.xml tests/phpunit/Unit/PromotionCalculationTest.php`

Expected: PASS.

```bash
git add src/web/app/Support/PromotionPrice.php src/web/app/Models/model.php src/web/app/Models/detailProduct.php tests/phpunit/Unit/PromotionCalculationTest.php
git commit -m "refactor: centralize promotion price calculation"
```

### Task 3: Test real cart state transitions

**Files:**
- Create: `src/web/app/Support/CartState.php`
- Modify: `src/web/app/Models/cart.php`
- Replace: `tests/phpunit/Unit/CartSessionTest.php`

- [ ] **Step 1: Write tests against `CartState`**

Cover add/merge by product and size, delete, plus, minus-at-one, stock ceiling, empty total, and shipping charged only for a non-empty cart.

- [ ] **Step 2: Verify failure**

Run: `src/web/vendor/bin/phpunit -c src/web/phpunit.xml tests/phpunit/Unit/CartSessionTest.php`

Expected: FAIL because `CartState` is missing.

- [ ] **Step 3: Implement deterministic state operations**

```php
final class CartState
{
    public const SHIPPING = 30000;

    public static function total(array $items): int
    {
        if ($items === []) return 0;
        return array_reduce(
            $items,
            fn (int $sum, array $item): int =>
                $sum + (int) ($item['d_price'] ?? $item['price']) * (int) $item['quantity'],
            self::SHIPPING
        );
    }
}
```

Implement `add`, `remove`, and `changeQuantity` in the same class with normalized array indexes and quantity bounded to `1..restQuantity`.

- [ ] **Step 4: Delegate session mutation from `Cart`**

Keep database lookup and checkout SQL in `Cart`; replace copied loops and total calculation with `CartState`. Preserve current routes and redirects.

- [ ] **Step 5: Run and commit**

Run: `src/web/vendor/bin/phpunit -c src/web/phpunit.xml tests/phpunit/Unit/CartSessionTest.php`

Expected: PASS.

```bash
git add src/web/app/Support/CartState.php src/web/app/Models/cart.php tests/phpunit/Unit/CartSessionTest.php
git commit -m "fix: make cart state transitions deterministic"
```

### Task 4: Bootstrap, routing, and PHP 8.4 error paths

**Files:**
- Create: `src/web/app/Support/RequestBootstrap.php`
- Modify: `src/web/public/index.php`
- Modify: `src/web/public/admin.php`
- Modify: `src/web/app/Views/error/error.php`
- Modify: `src/web/admin/Views/error/error.php`
- Replace: `tests/phpunit/Unit/RoutingTest.php`

- [ ] **Step 1: Add tests for bootstrap and route fallback**

Test one session start, default `home`, unknown public route to error view, unknown admin module to admin error view, and no output before redirects.

- [ ] **Step 2: Run and observe failing cases**

Run: `src/web/vendor/bin/phpunit -c src/web/phpunit.xml tests/phpunit/Unit/RoutingTest.php`

Expected: at least the unknown-route and bootstrap tests FAIL.

- [ ] **Step 3: Implement idempotent request bootstrap**

```php
final class RequestBootstrap
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
}
```

- [ ] **Step 4: Use explicit router defaults**

Call the bootstrap once from both entry points. Resolve routes using an explicit `switch` default that renders the existing error view and sets HTTP 404. Add `exit` after terminal redirects that are ordinary control flow; do not change challenge redirect behavior listed in the private contract.

- [ ] **Step 5: Verify PHP and tests**

Run: `find src/web -name '*.php' -print0 | xargs -0 -n1 php -l`

Run: `src/web/vendor/bin/phpunit -c src/web/phpunit.xml --testsuite Unit`

Expected: no syntax errors and all unit tests PASS.

- [ ] **Step 6: Commit**

```bash
git add src/web/app/Support/RequestBootstrap.php src/web/public/index.php src/web/public/admin.php src/web/app/Views/error/error.php src/web/admin/Views/error/error.php tests/phpunit/Unit/RoutingTest.php
git commit -m "fix: normalize request bootstrap and route fallbacks"
```

