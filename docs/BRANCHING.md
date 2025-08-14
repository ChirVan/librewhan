# Branching & Release Strategy

We follow a lightweight Git Flow inspired model tailored for a small team.

## Branch Types

| Branch | Purpose | Origin | Merge Target |
|--------|---------|--------|--------------|
| main | Production-ready, tagged releases | n/a | n/a |
| develop | Integration of completed features | main | main |
| feature/<topic> | New feature work | develop | develop |
| fix/<issue|topic> | Standard bug fix | develop | develop |
| hotfix/<critical> | Emergency production fix | main | main + develop |
| release/vX.Y.Z | (Optional) stabilize before tagging | develop | main + develop |

## Workflow Summary
1. Ensure local `develop` is current: `git checkout develop && git pull origin develop`
2. Create a branch: `git checkout -b feature/size-based-pricing`
3. Commit using Conventional Commits (examples below)
4. Push: `git push -u origin feature/size-based-pricing`
5. Open Pull Request into `develop`
6. Request review; address comments
7. Squash merge (preferred) or merge commit (if preserving history)
8. Release: merge `develop` -> `main`, tag, deploy

## Conventional Commit Examples
* feat: add size-based pricing for coffee
* fix: correct change calculation rounding
* refactor: extract cart total helper
* docs: add setup instructions for contributors
* chore: bump npm dependencies
* test: add order creation feature tests

## Tagging
Semantic versioning: `MAJOR.MINOR.PATCH`
* MAJOR – incompatible API changes
* MINOR – backwards-compatible feature additions
* PATCH – backwards-compatible bug fixes

Example:
```
git checkout main
git merge --ff-only develop
git tag v0.2.0
git push origin main --tags
```

## Hotfix Procedure
1. Branch from main: `git checkout main && git pull && git checkout -b hotfix/session-timeout`
2. Fix + commit
3. PR into main (or push & open directly if urgent)
4. After merge, fast-forward develop: `git checkout develop && git merge --ff-only main`

## Keeping Feature Branch Updated
Rebase to keep history linear and avoid noisy merge commits:
```
git fetch origin
git rebase origin/develop
```
If conflicts arise, resolve, continue rebase: `git rebase --continue`.

## Pull Request Checklist
- [ ] Feature / bug scope clearly described
- [ ] Tests added or updated (if applicable)
- [ ] No failing tests locally (`php artisan test`)
- [ ] Coding style passes (Laravel Pint if configured)
- [ ] Migrations reviewed (safe & idempotent)
- [ ] No debug dd()/dump() left

## CI (Future Enhancements)
Potential pipeline:
1. Install dependencies (Composer, NPM)
2. Lint (Pint / ESLint)
3. Static analysis (PHPStan)
4. Run tests (Pest)
5. Build assets

## FAQs
**Q:** Why not commit vendor/build assets?
**A:** We rely on Composer/NPM install steps; keeping repo lean and avoiding merge conflicts.

**Q:** Can I push directly to develop?
**A:** Avoid unless an emergency trivial fix (typo, docs). Prefer PR for review visibility.

**Q:** When do we delete branches?
**A:** After merge & deployment confirmation.

---
Happy branching!
