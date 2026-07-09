# Controlled Modernization Design

## Objective

Modernize BasketBallStore-PHP as a balanced storefront and security lab. Improve
runtime performance, user experience, maintainability, and non-security
correctness while preserving the URLs, data model, public features, and
intentional exploit chains used for pentesting.

## Constraints

- Keep the query-string routes and observable behavior used by existing clients
  and lab exercises.
- Keep intentional security weaknesses working. Do not convert deliberately
  unsafe queries or challenge flows into secure equivalents during refactoring.
- Fix ordinary PHP, UI, data, session, cart, promotion, redirect, and empty-state
  defects unless a regression test identifies the behavior as part of the lab.
- Avoid a framework migration and avoid large runtime dependencies.
- Keep private vulnerability details out of version control.

## Architecture

Retain the current PHP MVC shape and improve it incrementally. Introduce small
shared boundaries for application bootstrap and session initialization, view
rendering, promotion-price calculation, redirects, and JSON responses. Reuse
these boundaries from the public and admin controllers without changing route
names or request parameters.

Models remain responsible for database access. Repeated safe read paths may be
consolidated and narrowed to the columns actually used. Intentionally unsafe
data paths remain separate and covered by private regression checks so general
cleanup cannot silently remove a challenge.

The homepage and catalogue flows will reduce repeated query shapes and return
bounded datasets. Pagination will be added where an unbounded administrative or
catalogue listing currently grows with the database.

## Performance

Establish a baseline before implementation using the existing PHPUnit and
stress suites, asset sizes, representative response times, and database query
counts where practical.

Add indexes supporting existing joins, filters, and ordering, especially product
category/type/status/timestamp relationships and foreign-key lookup columns.
Index changes must not alter vulnerable query construction or exploitability.

Optimize static delivery by:

- generating WebP derivatives for oversized images while retaining originals
  required by upload and lab flows;
- using responsive image sources, explicit dimensions, and lazy loading below
  the fold;
- removing duplicate CSS/JavaScript delivery and deferring non-critical scripts;
- preserving cache busting while using long-lived immutable asset caching; and
- tuning Apache prefork and OPcache to the actual 256 MiB production container
  limit instead of advertising more PHP workers than memory can sustain.

## Storefront and Admin UX

Refresh the storefront with a consistent basketball-oriented visual system.
Unify spacing, typography, product cards, prices, promotions, controls, forms,
and feedback states. Maintain existing information architecture and URLs while
making navigation and shopping flows usable on small screens.

Improve image stability and perceived loading speed. Every major view must have
intentional empty, loading/transition, error, and success feedback where the
current interaction supports it.

Modernize admin tables and forms for clarity and responsive use without hiding
or removing lab-relevant controls. Destructive actions should be visually
distinct, but challenge behavior must remain intact.

## Correctness and Error Handling

Correct non-security defects in cart quantities and totals, promotion
calculation, null/empty data handling, route fallbacks, sessions, headers and
redirect termination, and PHP 8.4 warnings or type errors.

Database and controller failures should produce a stable user-facing fallback
and useful server-side diagnostics. Diagnostics must not expose the private
vulnerability inventory through the UI.

## Security-Lab Contract

Create `docs/private/security-lab-inventory.md` locally. The entire
`docs/private/` directory is ignored by Git. The inventory records each
intentional weakness, affected route, verification procedure, expected impact,
and regression status. Public documentation only states that the project is an
educational security lab and does not disclose solutions.

Existing intentional weaknesses are preserved even when they conflict with
normal production hardening. Code comments that directly reveal a challenge
should be replaced, where appropriate, by neutral implementation comments and
private inventory entries.

Add a new weakness only when the inventory shows a meaningful missing learning
category. A new challenge must be isolated to the web application or lab data,
have a deterministic reset path, and avoid host persistence, hidden backdoors,
or effects outside the Docker lab. Prefer a distinct business-logic or
file-handling lesson over another duplicate injection sink.

## Testing and Verification

Replace unit tests that merely copy production algorithms with tests that
execute the real implementation. Add focused tests for shared promotion and
cart logic, route compatibility, empty/error paths, and PHP 8.4 behavior.

Add integration and smoke coverage for:

- storefront home, catalogue, product detail, search, login, profile, cart, and
  checkout flows;
- admin list and form routes;
- database migrations/indexes and representative queries; and
- Docker build, startup, health, and static cache/compression headers.

Private security-contract checks confirm that intended exploit chains still
work after each relevant refactor. They remain outside version control with the
private inventory.

Verification compares the final state with the baseline: test results, HTTP
behavior, asset weight, representative latency, stress-test error rate, and
container memory use. A change is accepted only when it improves or preserves
these measures and does not break the lab contract.

## Delivery Sequence

1. Capture baselines and create the private security inventory.
2. Strengthen tests around real application behavior.
3. Fix non-security correctness defects and introduce small shared boundaries.
4. Optimize database access and schema indexes.
5. Optimize images, CSS, JavaScript, and HTTP delivery.
6. Refresh storefront and admin UI while preserving routes.
7. Re-run functional, performance, Docker, and private security verification.
8. Add at most one isolated challenge only if the inventory demonstrates a
   worthwhile coverage gap, then document and verify its reset behavior.

## Acceptance Criteria

- Existing public and admin URLs and core workflows remain compatible.
- Intentional vulnerabilities listed in the private inventory remain
  reproducible.
- No known non-security correctness regression remains in the covered flows.
- PHPUnit, integration, Docker, and smoke checks pass.
- Static payload and representative response/stress metrics improve from the
  recorded baseline.
- Storefront and admin views are responsive and have consistent feedback states.
- No vulnerability solution or payload is committed to the repository.
