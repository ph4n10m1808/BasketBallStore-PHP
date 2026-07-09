# Security-Lab Verification Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Preserve every intended pentest exercise across modernization and add no more than one isolated, resettable exercise if the private inventory shows a meaningful gap.

**Architecture:** Keep exploit details and verification scripts in Git-ignored `docs/private/` and `tests/private/`. Public CI verifies only that private artifacts cannot be committed; maintainers run the contract locally against the isolated Docker stack.

**Tech Stack:** Docker Compose, curl, PHP/Python test scripts kept outside Git, GitHub Actions policy check

---

### Task 1: Prevent accidental publication of private material

**Files:**
- Modify: `.gitignore`
- Create: `tests/policy/no-private-artifacts.sh`
- Modify: `.github/workflows/ci.yml`

- [ ] **Step 1: Extend ignore rules**

Ignore `docs/private/`, `tests/private/`, local proxy captures, and generated
pentest reports.

- [ ] **Step 2: Add a policy test**

Fail if `git ls-files` contains `docs/private`, `tests/private`, common payload
fixture names, or a line beginning `Exploit payload:` in committed Markdown.

- [ ] **Step 3: Run and wire into lint CI**

Run: `bash tests/policy/no-private-artifacts.sh`

Expected: PASS.

- [ ] **Step 4: Commit**

```bash
git add .gitignore tests/policy/no-private-artifacts.sh .github/workflows/ci.yml
git commit -m "ci: prevent publishing private lab material"
```

### Task 2: Build the local security contract

**Files:**
- Create: `tests/private/verify-lab.sh` (Git-ignored)
- Modify: `docs/private/security-lab-inventory.md` (Git-ignored)

- [ ] **Step 1: Give every weakness a stable ID**

Record category, route, prerequisite account/data, expected signal, cleanup, and
whether modernization may touch the path.

- [ ] **Step 2: Implement isolated checks**

The runner requires `LAB_BASE_URL`, refuses non-loopback/private RFC1918 hosts
unless `ALLOW_REMOTE_LAB=1`, checks Docker Compose project identity, executes
one check per inventory ID, and always runs cleanup.

- [ ] **Step 3: Run the complete contract**

Run: `LAB_BASE_URL=http://127.0.0.1 bash tests/private/verify-lab.sh`

Expected: every documented challenge reports PASS and lab data is restored.

### Task 3: Decide whether to add one challenge

**Files:**
- Modify only after inventory review: one existing middleware/controller/model path
- Modify: `docs/private/security-lab-inventory.md`
- Modify: `tests/private/verify-lab.sh`

- [ ] **Step 1: Produce a category coverage matrix**

Count distinct learning outcomes, not sinks. If business logic or file handling
already has a deterministic exercise, do not add another vulnerability.

- [ ] **Step 2: If a gap exists, write the failing private contract check**

The check must prove normal behavior, exploit behavior, and cleanup before code
is changed.

- [ ] **Step 3: Implement one minimal isolated exercise**

Keep it inside the existing Docker web app and seeded lab data. Do not add
host command execution, persistence, hidden credentials, outbound callbacks, or
backdoors. Do not annotate the public code with a solution.

- [ ] **Step 4: Verify reset and the full suite**

Run private contract twice from a clean Compose database.

Expected: identical PASS results on both runs and no state drift.

- [ ] **Step 5: Commit only application behavior**

Before committing, run `bash tests/policy/no-private-artifacts.sh`; inspect
`git diff --cached` and confirm no private inventory, payload, or verification
script is staged.

### Task 4: Final cross-plan acceptance

**Files:**
- Modify: `README.md`

- [ ] **Step 1: Run public verification**

Run PHP lint, all PHPUnit suites, both smoke scripts, Docker build/start with
`--wait`, actionlint, baseline collector, and stress tests.

- [ ] **Step 2: Run private verification**

Run the security contract twice, reset the DB, and verify it a third time.

- [ ] **Step 3: Compare metrics**

Require lower static transfer size, no worse representative response latency or
stress error rate, stable container memory, and all compatible URLs returning
non-5xx responses.

- [ ] **Step 4: Document public operation only**

Update README with local startup, test, CI/CD image-tag, rollback, performance,
and isolated-lab instructions. Do not disclose challenge routes or solutions.

- [ ] **Step 5: Commit**

```bash
git add README.md
git commit -m "docs: document optimized lab operation"
```
