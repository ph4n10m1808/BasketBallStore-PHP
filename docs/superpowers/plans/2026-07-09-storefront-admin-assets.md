# Storefront, Admin, and Asset Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Deliver a responsive, consistent basketball storefront and usable admin UI with materially smaller and more stable page loads.

**Architecture:** Preserve the existing PHP views and URLs. Introduce small reusable view partials and one design-token layer, then optimize image delivery and script loading without changing server-side challenge behavior.

**Tech Stack:** PHP templates, semantic HTML, CSS custom properties, vanilla JavaScript/jQuery where already required, GD/WebP

---

### Task 1: Establish view smoke and accessibility checks

**Files:**
- Create: `tests/smoke/view_smoke.sh`
- Modify: `.github/workflows/ci.yml`

- [ ] **Step 1: Write checks**

For home, product list, detail, cart, login, and admin, require one `<main>`,
one `<h1>`, a viewport meta tag, no broken local image URLs, and no duplicate
HTML IDs in the rendered response.

- [ ] **Step 2: Run against Compose**

Run: `BASE_URL=http://localhost bash tests/smoke/view_smoke.sh`

Expected: FAIL on current structural inconsistencies; save the failures as the UI baseline.

- [ ] **Step 3: Add the script to CI after the related pages pass**

Run both HTTP and view smoke scripts in the Compose smoke step.

- [ ] **Step 4: Commit**

```bash
git add tests/smoke/view_smoke.sh .github/workflows/ci.yml
git commit -m "test: add rendered view smoke checks"
```

### Task 2: Introduce the storefront design system

**Files:**
- Create: `src/web/public/assets/css/tokens.css`
- Create: `src/web/app/Views/components/productCard.php`
- Modify: `src/web/app/Views/header/header.php`
- Modify: `src/web/app/Views/footer/footer.php`
- Modify: `src/web/public/assets/css/main.css`
- Modify: `src/web/public/assets/css/home.css`
- Modify: `src/web/public/assets/css/premium.css`

- [ ] **Step 1: Add tokens**

Define `--color-court`, `--color-ink`, `--color-surface`, `--color-accent`,
spacing from `--space-1` through `--space-8`, two radii, shadows, content width,
and responsive type sizes using `clamp()`.

- [ ] **Step 2: Build the product-card partial**

The partial accepts `$product`, prints the existing detail URL, promotion label,
original/current prices, image with width/height, and a meaningful alt based on
`name_product`. It must not escape or normalize challenge-controlled fields
listed in the private lab contract.

- [ ] **Step 3: Normalize the page shell**

Add skip link, semantic header/nav/main/footer boundaries, visible keyboard
focus, mobile navigation, and consistent flash/error containers.

- [ ] **Step 4: Replace repeated home product markup**

Use the partial from shoes, pants, shirts, accessories, newest, and related
sections while retaining the same datasets and URLs.

- [ ] **Step 5: Verify and commit**

Run: `BASE_URL=http://localhost bash tests/smoke/view_smoke.sh`

Expected: home and catalogue checks PASS.

```bash
git add src/web/public/assets/css src/web/app/Views/components src/web/app/Views/header src/web/app/Views/footer src/web/app/Views/home
git commit -m "feat: add responsive storefront design system"
```

### Task 3: Modernize transactional views

**Files:**
- Modify: `src/web/app/Views/detail/detail.php`
- Modify: `src/web/app/Views/cart/cart.php`
- Modify: `src/web/app/Views/cart/product.php`
- Modify: `src/web/app/Views/cart/empty.php`
- Modify: `src/web/app/Views/login/login.php`
- Modify: `src/web/app/Views/login/register.php`
- Modify: `src/web/app/Views/profile/profile.php`
- Modify: `src/web/app/Views/search/search.php`

- [ ] **Step 1: Normalize forms and messages**

Every input gets a label, current validation text remains visible, buttons have
specific labels, totals use one formatter, and empty/error states provide a
useful route back to shopping.

- [ ] **Step 2: Stabilize product media**

Use an aspect-ratio container, thumbnail buttons with accessible names, and
`loading="lazy"` only below the primary detail image.

- [ ] **Step 3: Make cart controls resilient**

Keep existing middleware URLs and parameters. Disable only the visible minus
control at quantity one and the plus control at known stock, while server-side
`CartState` remains authoritative.

- [ ] **Step 4: Verify mobile rendering**

Run the view smoke test and use browser widths 360, 768, and 1440; require no
horizontal overflow and reachable form controls.

- [ ] **Step 5: Commit**

```bash
git add src/web/app/Views/detail src/web/app/Views/cart src/web/app/Views/login src/web/app/Views/profile src/web/app/Views/search
git commit -m "feat: modernize shopping and account views"
```

### Task 4: Optimize image and script delivery

**Files:**
- Create: `tools/optimize-images.sh`
- Modify: `src/web/app/Views/home/banner.php`
- Modify: `src/web/public/assets/js/main.js`
- Modify: `src/web/app/Views/index.php`
- Modify: `src/web/Dockerfile`

- [ ] **Step 1: Add a deterministic image optimizer**

Generate `.webp` beside JPG/PNG files larger than 100 KiB, preserve originals,
skip files whose WebP already exists and is newer, and print before/after bytes.

- [ ] **Step 2: Run and record savings**

Run: `bash tools/optimize-images.sh src/web/public/assets/imgs`

Expected: WebP derivatives are created and total derivative bytes are lower
than their sources.

- [ ] **Step 3: Serve responsive formats**

Use `<picture>` with WebP source and original fallback for banners and product
cards; add explicit dimensions and below-fold lazy loading.

- [ ] **Step 4: Remove duplicate blocking delivery**

Load one jQuery build only where required, add `defer` to application scripts,
and remove repeated CSS/JS tags from nested views. Extend immutable caching to
WebP and version local CSS/JS URLs from file modification time.

- [ ] **Step 5: Verify payload and commit**

Run the baseline collector and compare `/tmp/basketballstore-baseline/assets.txt`.
Run HTTP/view smoke tests.

```bash
git add tools/optimize-images.sh src/web/public/assets/imgs src/web/app/Views src/web/public/assets/js/main.js src/web/Dockerfile
git commit -m "perf: optimize responsive image and script delivery"
```

### Task 5: Refresh admin views

**Files:**
- Modify: `src/web/public/assets/css/admin-premium.css`
- Modify: `src/web/admin/Views/header/header.php`
- Modify: `src/web/admin/Views/slidebar/slidebar.php`
- Modify: `src/web/admin/Views/index.php`
- Modify: `src/web/admin/Views/*/list*.php`
- Modify: `src/web/admin/Views/*/add*.php`
- Modify: `src/web/admin/Views/*/edit*.php`

- [ ] **Step 1: Add admin tokens and responsive shell**

Use the storefront color/type tokens, compact navigation, overflow-safe tables,
consistent status badges, form grids, and sticky mobile action bars.

- [ ] **Step 2: Normalize list and form semantics**

Give tables captions, headers `scope="col"`, actionable empty rows, associated
labels, and visible validation/errors. Preserve all existing field names,
routes, and actions.

- [ ] **Step 3: Verify representative modules**

Smoke account, product, promotion, bill, banner, and category list/add/edit
routes at 360 and 1440 widths.

- [ ] **Step 4: Commit**

```bash
git add src/web/public/assets/css/admin-premium.css src/web/admin/Views
git commit -m "feat: refresh responsive admin interface"
```

