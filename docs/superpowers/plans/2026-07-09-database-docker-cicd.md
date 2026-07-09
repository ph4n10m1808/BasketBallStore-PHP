# Database, Docker, and CI/CD Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Improve query performance, container reliability, and delivery confidence while keeping security-lab exploit behavior reproducible.

**Architecture:** Add non-semantic indexes and measurable query improvements, right-size Apache/PHP to container memory, and make CI prove syntax, behavior, image startup, health, headers, and release correctness before CD publishes images.

**Tech Stack:** MariaDB, PHP 8.4 Apache image, Docker Compose, GitHub Actions, GHCR

---

### Task 1: Add query-plan regression coverage and indexes

**Files:**
- Modify: `src/db/dbstore.sql`
- Modify: `tests/phpunit/Integration/DatabaseIntegrationTest.php`

- [ ] **Step 1: Write failing index assertions**

Assert composite indexes named `idx_product_category_status_timestamp`,
`idx_product_type_status_stars`, and `idx_banner_status_timestamp` exist in
`information_schema.statistics`.

- [ ] **Step 2: Run integration tests**

Run: `src/web/vendor/bin/phpunit -c src/web/phpunit.xml tests/phpunit/Integration/DatabaseIntegrationTest.php`

Expected: FAIL with missing indexes.

- [ ] **Step 3: Add indexes to table definitions**

```sql
KEY `idx_product_category_status_timestamp`
  (`id_category`, `status`, `timestamp`),
KEY `idx_product_type_status_stars`
  (`id_product_type`, `status`, `n_stars`),
KEY `idx_banner_status_timestamp`
  (`status`, `timestamp`)
```

Place each key in its owning table definition. Do not add constraints or query sanitization.

- [ ] **Step 4: Verify and commit**

Recreate the test DB from `src/db/dbstore.sql`, run the integration test, and expect PASS.

```bash
git add src/db/dbstore.sql tests/phpunit/Integration/DatabaseIntegrationTest.php
git commit -m "perf: add storefront query indexes"
```

### Task 2: Right-size and health-check the web image

**Files:**
- Create: `src/web/public/health.php`
- Modify: `src/web/Dockerfile`
- Modify: `docker-compose.yml`
- Modify: `docker-compose.dev.yml`
- Modify: `tests/phpunit/Integration/DockerBuildTest.php`

- [ ] **Step 1: Add failing Dockerfile/config assertions**

Require a web healthcheck, `MaxRequestWorkers` no greater than 32 under 256 MiB,
OPcache at least 96 MiB, and health endpoint response `ok`.

- [ ] **Step 2: Run the Docker integration test**

Run: `src/web/vendor/bin/phpunit -c src/web/phpunit.xml tests/phpunit/Integration/DockerBuildTest.php`

Expected: FAIL on worker count and missing web healthcheck.

- [ ] **Step 3: Add a dependency-light health endpoint**

```php
<?php
header('Content-Type: text/plain; charset=utf-8');
http_response_code(200);
echo 'ok';
```

- [ ] **Step 4: Tune runtime and Compose health**

Set `StartServers 4`, `MinSpareServers 4`, `MaxSpareServers 12`,
`MaxRequestWorkers 24`, `MaxConnectionsPerChild 1000`; set OPcache memory to
`96`, accelerated files to `4000`. Add a web healthcheck using
`curl --fail http://localhost/health.php`, installing runtime `curl` in the
image.

- [ ] **Step 5: Build and smoke test**

Run: `docker compose -f docker-compose.dev.yml build`

Run: `docker compose -f docker-compose.dev.yml up -d --wait`

Run: `curl -fsS http://localhost/health.php`

Expected: `ok`.

- [ ] **Step 6: Commit**

```bash
git add src/web/public/health.php src/web/Dockerfile docker-compose.yml docker-compose.dev.yml tests/phpunit/Integration/DockerBuildTest.php
git commit -m "perf: right-size web container and add healthcheck"
```

### Task 3: Make CI deterministic and security-lab aware

**Files:**
- Modify: `.github/workflows/ci.yml`
- Create: `tests/smoke/http_smoke.sh`

- [ ] **Step 1: Create the HTTP smoke script**

Check `/`, `/?page=product&type=shoes`, `/?page=cart`, `/admin.php`, and
`/health.php`; require non-5xx status, then verify gzip and long-lived cache
headers on a CSS asset.

- [ ] **Step 2: Run against the local Compose stack**

Run: `BASE_URL=http://localhost bash tests/smoke/http_smoke.sh`

Expected: PASS.

- [ ] **Step 3: Update CI jobs**

Use `composer.lock` for every Composer cache key; run `composer validate
--strict`; replace fixed `sleep 15` with `docker compose ... up -d --wait`;
execute the smoke script; always upload Compose logs on failure; always run
`docker compose down -v`.

- [ ] **Step 4: Validate workflow syntax**

Run: `docker run --rm -v "$PWD:/repo" rhysd/actionlint:latest`

Expected: no findings.

- [ ] **Step 5: Commit**

```bash
git add .github/workflows/ci.yml tests/smoke/http_smoke.sh
git commit -m "ci: add deterministic compose smoke verification"
```

### Task 4: Separate verification from release publication

**Files:**
- Modify: `.github/workflows/cd.yml`
- Modify: `README.md`

- [ ] **Step 1: Restrict CD triggers**

Build and push `sha-*` images on the default branch, but create GitHub Releases
and semver tags only for an existing `v*.*.*` tag. Manual staging dispatch may
publish a `staging` tag; it must not synthesize a release tag.

- [ ] **Step 2: Pin deployment concurrency**

Add:

```yaml
concurrency:
  group: cd-${{ github.ref }}
  cancel-in-progress: false
```

Keep least-privilege job permissions and make build-and-push depend on reusable CI.

- [ ] **Step 3: Document image tags and rollback**

Document `sha-<commit>`, semver, `staging`, and `latest`; include rollback by
setting `APP_VERSION` to a known semver/SHA and recreating Compose services.

- [ ] **Step 4: Validate and commit**

Run: `docker run --rm -v "$PWD:/repo" rhysd/actionlint:latest`

Expected: no findings.

```bash
git add .github/workflows/cd.yml README.md
git commit -m "ci: make releases tag-driven and rollbackable"
```

