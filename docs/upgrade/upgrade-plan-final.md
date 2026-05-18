# Fujika Industries — Full Upgrade Plan
**Project:** Fujika Industries Catalog & Admin Panel  
**Date:** 2026-04-26  
**Prepared by:** Ahmed Achfakay (devdreame@gmail.com)
**Upgrade Path:** Laravel 5.4 / PHP 5.6 → Laravel 11 / PHP 8.2  
**Budget:** $250.00 | **Duration:** 7 Days

---

## 1. Executive Summary

### Project Statistics

| Metric | Value |
|---|---|
| Total PHP files | ~55 |
| Estimated lines of PHP code | ~3,700 |
| Models | 5 (`User`, `category`, `product`, `slider`, `store`) |
| Controllers | 18 (including one unnamed `.php` / `currency` class) |
| Migrations | 13 (severely outdated — do not reflect live schema) |
| Route definitions | ~140 (many duplicated; effective unique routes ~80) |
| Views (Blade) | ~73 files across `Layout/`, `Pages/`, `web/` |
| Test files | 0 (zero automated tests) |
| Third-party packages | 5 (all Laravel core — no custom packages) |

### Total Issues Found

| Category | Count | Critical |
|---|---|---|
| N+1 / Query-in-loop | 4 | — |
| Security vulnerabilities | 9 | 2 CRITICAL |
| Deprecated / removed code | 10 | — |
| Code quality issues | 9 | — |
| Incompatible packages | 5 | — |
| **Total** | **37** | **2** |

### Upgrade Complexity Rating: **HIGH**

**Justification:**
- 7 consecutive major version jumps (L5.4 → L6 → L7 → L8 → L9 → L10 → L11)
- Requires a PHP version jump from 5.6 to 8.2 (3 major versions)
- The most disruptive single change — string-based route syntax removal in L8 — affects every single route in `routes/web.php` (~80 routes)
- L11 removes the HTTP Kernel entirely, requiring a full `bootstrap/app.php` rewrite
- Zero test coverage means every step carries risk of undetected regression
- Migrations do not match live schema — requires reverse-engineering before any `migrate` command can be safely run
- 2 CRITICAL security issues (plaintext passwords, unprotected admin panel) that ideally should be fixed before going live on the upgraded stack

---

## 2. Audit Findings Table

| File | Line(s) | Issue Type | Severity | Fix |
|---|---|---|---|---|
| `app/Http/Controllers/web_api.php` | 480 | N+1 Query | High | Pre-load all `order_items` with single `whereIn` query keyed by `order_id` |
| `app/Http/Controllers/web_api.php` | 392, 400 | N+1 Query | High | Pre-load all products via `whereIn`; update quantities in bulk |
| `app/Http/Controllers/web_api.php` | 568–570 | N+1 Query | High | Collect IDs; use bulk `whereIn` delete and single `update` |
| `app/Http/Controllers/website.php` | 277–278 | N+1 Query | Medium | Add `MAX(q)` / `MAX(h)` subquery grouped by `Model` to primary query |
| `app/Http/Controllers/web_api.php` | 52–58, 278, 349 | Security — Plaintext Passwords | CRITICAL | `Hash::make()` on store/update; `Hash::check()` on login |
| `routes/web.php` | 13–314 | Security — No Auth Middleware | CRITICAL | Wrap all admin routes in `Route::middleware(['auth'])` |
| `app/Http/Controllers/website.php` | 134, 145 | Security — Hardcoded Credentials | High | Move to `.env` / `config/mail.php` |
| `app/Http/Controllers/web_api.php` | 146, 148, 153, 157, 179, 189 | Security — Hardcoded Verify Code `'123'` | High | Use `Str::random(6)` for real codes |
| `app/Http/Controllers/web_api.php` | 36–302 (all methods) | Security — Unvalidated `$_REQUEST` | High | Replace with `$request->input()` + `FormRequest` validation |
| `app/Http/Controllers/web_api.php` | 19 | Security — Commented SQL Injection Query | Medium | Delete the commented-out line |
| `routes/web.php` | all | Security — No Role-Based Access Control | Medium | Add Gate / Policy / `spatie/laravel-permission` |
| `routes/web.php` | 244 | Security — No Rate Limiting on Login | Medium | Apply `throttle:5,1` to `POST checklogin` |
| `app/Http/Controllers/system_user.php` | 61, 115 | Security — Password Hash in Session | Low | Use `Hash::check()` directly; remove session caching |
| `routes/web.php` | 23–311 | Deprecated — String Route Syntax | Breaking (L8) | Convert all routes to array syntax `[ControllerClass::class, 'method']` |
| `app/Providers/RouteServiceProvider.php` | 17, 55 | Deprecated — `$namespace` Property | Breaking (L8) | Remove property and `->namespace()` calls |
| `app/Http/Kernel.php` | 17 | Deprecated — `CheckForMaintenanceMode` | Breaking (L8) | Rename to `PreventRequestsDuringMaintenance` |
| `app/Http/Kernel.php` | 41 | Deprecated — `'bindings'` alias | Breaking (L8) | Replace with `SubstituteBindings::class` |
| `app/Http/Kernel.php` | entire file | Deprecated — HTTP Kernel removed | Breaking (L11) | Migrate middleware to `bootstrap/app.php` |
| `app/Providers/RouteServiceProvider.php` | entire file | Deprecated — RouteServiceProvider removed | Breaking (L11) | Migrate to `bootstrap/app.php` `withRouting()` |
| `composer.json` | 9 | Package — `laravel/framework:5.4.*` | Breaking | Upgrade to `^11.0` incrementally |
| `composer.json` | 10 | Package — `laravel/tinker:~1.0` | Incompatible | Upgrade to `^2.9` |
| `composer.json` | 13 | Package — `fzaninotto/faker` ABANDONED | Incompatible | Replace with `fakerphp/faker:^1.23` |
| `composer.json` | 14 | Package — `mockery/mockery:0.9.*` | Incompatible | Upgrade to `^1.6` |
| `composer.json` | 15 | Package — `phpunit/phpunit:~5.7` | Incompatible | Upgrade to `^11.0` |
| `app/category.php`, `product.php`, `slider.php`, `store.php` | all | Quality — Empty Models, No `$fillable` | High | Add `$fillable`, `$table`, relationships |
| All 18 controllers | all | Quality — No Return Type Hints | Medium | Add `View`, `RedirectResponse`, `JsonResponse` return types |
| `routes/web.php` | 129–314 | Quality — Duplicate Route Definitions | High | Remove the duplicate block |
| All controllers | all | Quality — `$_POST`/`$_GET`/`$_REQUEST` Superglobals | High | Replace with `$request->input()` |
| All controllers | all | Quality — Lowercase Class Names (PSR-1) | Medium | Rename to `StudlyCaps` + `Controller` suffix |
| `app/Http/Controllers/.php` | all | Quality — Unnamed File / Orphan Controller | Medium | Rename file to `CurrencyController.php`; add routes |
| `database/migrations/` | all 13 files | Quality — Schema Does Not Match Live DB | Critical | Regenerate with `kitloong/laravel-migrations-generator` |
| `routes/web.php` | 231–239 | Quality — Missing `OrderController` | High | Create `app/Http/Controllers/OrderController.php` |
| `app/Http/Controllers/product.php` | — | Quality — Missing `check_validity()` Methods | High | Implement or remove routes |
| `webpack.mix.js` | all | Deprecated — Laravel Mix v1 | Breaking (L11) | Migrate to Vite |

---

## 3. Timeline (7 Days)

| Day | Phase | Tasks | Risk Level |
|---|---|---|---|
| **Day 1** | **Preparation** | Clone repo + DB; reverse-engineer migrations with `kitloong/laravel-migrations-generator`; set up PHP 7.2 in local env; create manual test checklist; set up Git branch `upgrade/laravel-11` | Low |
| **Day 1** | **L5.4 → L6 (Steps 1–5)** | Upgrade through L5.5 → L5.6 → L5.7 → L5.8 → L6.x; replace `fzaninotto/faker` with `fakerphp/faker`; verify route list and manual tests after each sub-step | Medium |
| **Day 2** | **L6 → L8 (Steps 6–7)** | Upgrade L6 → L7 → L8; **convert all 80+ routes** from string syntax to array syntax; remove `$namespace` from `RouteServiceProvider`; fix `Kernel.php` middleware classes; upgrade tinker to v2 | **High** |
| **Day 2** | **PHP 8.0 + L9 (Step 8)** | Upgrade PHP to 8.0; upgrade to L9; audit all `$_REQUEST` usages for missing `?? ''` null-safety; check deprecation log | High |
| **Day 3** | **PHP 8.1 + L10 (Step 9)** | Upgrade PHP to 8.1; upgrade to L10; upgrade mockery and phpunit; run full manual test checklist | Medium |
| **Day 4** | **PHP 8.2 + L11 (Step 10)** | Upgrade PHP to 8.2; upgrade to L11; rewrite `bootstrap/app.php`; remove `Kernel.php` and `RouteServiceProvider`; move models to `App\Models`; update auth config | **High** |
| **Day 5** | **Security Fixes** | Fix CRITICAL issues: add auth middleware to all admin routes; hash all passwords in `web_api.php`; replace hardcoded verify codes; move emails to `.env`; add rate limiting | Medium |
| **Day 6** | **Code Quality & Cleanup** | Rename controllers to StudlyCaps; add `$fillable` to models; replace all `$_REQUEST` superglobals with `$request->input()`; create missing `OrderController`; remove duplicate routes; migrate to Vite | Medium |
| **Day 7** | **Testing & Deployment** | Write smoke tests for all critical paths; run `php artisan test`; staging deployment; performance comparison; production deployment with rollback plan | Medium |

---
## 4. Risk Matrix

| Risk | Probability | Impact | Risk Level | Mitigation Plan |
|---|---|---|---|---|
| Route conversion breaks URL structure or named routes | High | High | 🔴 High | Test every route in `php artisan route:list` after conversion; smoke test all admin panel pages |
| Outdated migrations cause `migrate:fresh` to fail | High | High | 🔴 High | Regenerate migrations before any upgrade step; never run `migrate:fresh` on live DB |
| PHP 8.0 `$_REQUEST['key']` undefined index TypeError crashes app | High | High | 🔴 High | Audit all `$_REQUEST` reads and add `?? ''` null coalescing before Step 8 |
| `bootstrap/app.php` rewrite in L11 breaks middleware stack | Medium | High | 🔴 High | Test each middleware individually; compare before/after request lifecycle |
| Zero test coverage means regressions go undetected | High | High | 🔴 High | Build manual test checklist before starting; run it after every step |
| Plaintext password migration breaks user logins | High | High | 🔴 High | Plan a coordinated password reset: force all `web_api` users to reset password before hashing |
| `web_api.php` used by a mobile app — URL/response contract may break | Medium | High | 🔴 High | Document all API responses before upgrading; compare after each step |
| `fzaninotto/faker` removal breaks seeders | Low | Low | 🟢 Low | Replace with `fakerphp/faker`; API is identical |
| Laravel Mix → Vite migration breaks CSS/JS compilation | Medium | Medium | 🟡 Medium | Keep Mix initially; migrate to Vite as a separate task after L11 is stable |
| New PHP 8.2 dynamic property deprecations on custom classes | Low | Low | 🟢 Low | Scan classes; `DB::table()` stdClass objects are not affected |
| Missing `OrderController` and `check_validity` methods cause 500 errors | High | Medium | 🟡 Medium | Create stub controllers before going to production |
| Admin panel is publicly accessible during upgrade period | High | Critical | 🔴 High | Add auth middleware in Day 5 immediately; do not delay this |

---

## 5. Expected Results After Upgrade

| Metric | Before (L5.4 / PHP 5.6) | After (L11 / PHP 8.2) |
|---|---|---|
| **PHP Performance** | PHP 5.6 — slow JIT-less engine | PHP 8.2 — JIT compiler, ~3× faster in CPU-bound work, ~40% faster in typical web workloads |
| **Framework Security** | Laravel 5.4 — EOL since 2018, no security patches | Laravel 11 — actively maintained, security patches, CVE coverage |
| **Query Performance** | 4 N+1 patterns; up to 2N+1 queries per order history load | 4 N+1 patterns resolved; single-query loads per page |
| **Password Security** | Plaintext storage in `user_profiles` | bcrypt hashing via `Hash::make()` |
| **Admin Panel Security** | No authentication on any admin route | All admin routes protected by `auth` middleware + role checks |
| **Dependency Status** | 5 packages: all outdated, 1 abandoned | 5 packages: all current and maintained |
| **Developer Tooling** | No Telescope, no Pulse, no typed models | Can install Telescope, Pulse, Pint, PHPStan, Larastan |
| **IDE Support** | Minimal (old string-based routes are not navigable) | Full IDE support with array route syntax + FQCN controllers |
| **Test Coverage** | 0% | Target: >80% with Feature tests |
| **Community Support** | None — L5.4 is EOL | Full — L11 is current LTS-adjacent release |
| **Maintainability** | Low — PSR-1 violations, superglobals, no types | High — PSR-12, typed methods, FormRequests, policies |

---

## 6. KPIs to Measure Success

| KPI | Target | Measurement Tool |
|---|---|---|
| N+1 query count | 0 | Laravel Debugbar (`barryvdh/laravel-debugbar`) — query count per page |
| Security: Admin routes require authentication | 100% of admin routes protected | `php artisan route:list` + manual curl test without session |
| Password storage | All passwords hashed with bcrypt | Direct DB query: `SELECT account_password FROM user_profiles LIMIT 5` — must show `$2y$...` |
| Deprecated function warnings | 0 in `storage/logs/laravel.log` | `grep -i "deprecated" storage/logs/laravel.log` |
| Test coverage | > 80% | `php artisan test --coverage` (requires Xdebug or pcov) |
| Response time — homepage | < 200 ms | Lighthouse / curl timing: `curl -o /dev/null -s -w "%{time_total}" http://fujika.local/` |
| Response time — filter page | < 500 ms | Same as above on `/filter?cat_id=1` |
| `php artisan route:list` errors | 0 | `php artisan route:list` must exit with code 0 |
| PHP deprecation notices | 0 | `php -d error_reporting=E_ALL artisan route:list` |
| `composer audit` vulnerabilities | 0 | `composer audit` |
| PSR-12 violations | 0 | `./vendor/bin/pint --test` (Laravel Pint) |
| Static analysis errors (PHPStan L5) | 0 | `./vendor/bin/phpstan analyse app/ --level=5` |

---

## 7. Recommended Next Steps

### Immediate Priority (Before any upgrade — do this week)

1. **Add `auth` middleware to admin routes** — This is a 5-minute change that closes a CRITICAL security hole. Even on L5.4, wrapping routes in `Route::middleware('auth')` works.
2. **Stop using plaintext passwords in `web_api.php`** — Any new user registration must use `Hash::make()` immediately. Existing records require a password reset campaign.
3. **Back up the live database and codebase** — No upgrades without a verified backup.
4. **Regenerate migrations** — Install `kitloong/laravel-migrations-generator` and generate accurate schema files. Without this, the upgrade cannot proceed safely.

### High Priority (Day 1–2)

5. **Set up a staging environment** — Clone the full server (PHP, MySQL, Apache) to a local or cloud staging environment. Never upgrade on production.
6. **Build a manual regression test checklist** — Document every URL and expected behavior. This is the only safety net with zero automated tests.
7. **Create `OrderController.php`** and stub `check_validity` methods — Fix the broken routes before they cause confusion during upgrade.
8. **Remove duplicate routes** from `routes/web.php` — Clean up before the L8 conversion step.

### Medium Priority (Days 3–6)

9. **Convert all routes to array syntax** — Required for L8, largest single change in the project.
10. **Replace all `$_REQUEST`/`$_POST`/`$_GET`** with `$request->input()` across all controllers.
11. **Rename all controllers** to PSR-1 `StudlyCaps` with `Controller` suffix.
12. **Add `$fillable` and relationships** to all Eloquent models.
13. **Write Feature tests** for every controller action — Aim for 80%+ coverage.
14. **Migrate asset pipeline** from Laravel Mix v1 to Vite.

### Post-Upgrade (After Day 7)

15. **Install Laravel Telescope** for query and performance monitoring.
16. **Install Laravel Pint** for automated code style enforcement.
17. **Run PHPStan at level 5** and resolve all static analysis errors.
18. **Set up GitHub Actions** CI/CD pipeline with `php artisan test` on every pull request.
19. **Implement proper RBAC** using `spatie/laravel-permission` to replace the custom `role` column logic.
20. **Replace hardcoded verification codes** with a real SMS/email OTP delivery system.
