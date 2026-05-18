# Fujika Industries — Step-by-Step Upgrade Guide
**From:** Laravel 5.4 / PHP 5.6  
**To:** Laravel 11 / PHP 8.2  
**Generated:** 2026-04-26  
**Prepared by:** Ahmed Achfakay (devdreame@gmail.com)

> ⚠️ **RULE:** Never perform any step on the live production server. Work on a full clone of the project with a copy of the live database. Each step must pass verification before proceeding to the next.

---

## Pre-Upgrade Preparation (Do This First)

### P1 — Create a Git branch and full database backup
```bash
git checkout -b upgrade/laravel-11
mysqldump -u root -p fujika_db > fujika_db_backup_$(date +%Y%m%d).sql
```

### P2 — Upgrade PHP to 7.4 first (intermediate step)
Laravel 5.4 runs on PHP 5.6, but you need to reach PHP 8.2 incrementally. Install PHP 7.4 in XAMPP alongside your current setup, or use a Docker environment.

### P3 — Reverse-engineer the REAL database schema into new migrations
The existing 13 migration files do not reflect the actual live schema. Before any upgrade:

```bash
# Install the migration generator (works on L5.4)
composer require --dev kitloong/laravel-migrations-generator
php artisan migrate:generate
```

This produces accurate migration files. Review them, then delete the old 13 migration files and replace them. This is critical because `php artisan migrate:fresh` will destroy the DB otherwise.

### P4 — Establish a test baseline
Run the application manually and document every page that works. Since there are no automated tests, create a checklist:
- [ ] Homepage loads
- [ ] Category page loads
- [ ] Filter page works
- [ ] Admin login works
- [ ] Category CRUD works
- [ ] Product CRUD works
- [ ] Product parameter CSV import works

---

## Step 1 — Laravel 5.4 → 5.5

### Composer command
```bash
composer require laravel/framework:5.5.* --no-update
composer require laravel/tinker:~1.0 --no-update
composer update
```

### Breaking changes affecting THIS codebase

**None that require code changes.** Laravel 5.5 is largely backwards compatible with 5.4 for this codebase. Key additions (auto package discovery, `$request->validate()`) are additive only.

### Verification
```bash
php artisan optimize:clear
php artisan route:list
php artisan serve
```
- Manually test: homepage, admin login, category list.

---

## Step 2 — Laravel 5.5 → 5.6

### Composer command
```bash
composer require laravel/framework:5.6.* --no-update
composer update
```

### Breaking changes affecting THIS codebase

**1. Logging system rewritten** — `config/app.php` still has the old `log` key. Add `config/logging.php` (Laravel provides a publishable default):
```bash
php artisan vendor:publish --tag=laravel-config --force
```
Keep your existing `config/app.php` settings; just add the new `logging.php`.

**2. Argon2 password hashing added** — No change needed; bcrypt is still the default and is used by `system_user` controller.

### Verification
```bash
php artisan optimize:clear
php artisan route:list
php artisan serve
```

---

## Step 3 — Laravel 5.6 → 5.7

### Composer command
```bash
composer require laravel/framework:5.7.* --no-update
composer update
```

### Breaking changes affecting THIS codebase

**1. `Str` and `Arr` facades added** — No breaking changes, but you can begin migrating `str_*` and `array_*` global helpers to `Str::` and `Arr::` static calls (required in Step 5).

**2. `RedirectIfAuthenticated` middleware** — The middleware at `app/Http/Middleware/RedirectIfAuthenticated.php` now needs to return a response. Verify it compiles cleanly.

### Verification
```bash
php artisan optimize:clear
php artisan route:list
php artisan serve
```

---

## Step 4 — Laravel 5.7 → 5.8

### Composer command
```bash
composer require laravel/framework:5.8.* --no-update
composer update
```

### Breaking changes affecting THIS codebase

**1. Cache TTL changed from minutes to seconds**  
Affects: `config/cache.php` — the `'ttl'` values are now in seconds. If you use `Cache::put($key, $val, 10)`, that is now 10 seconds, not 10 minutes. This app does not use explicit cache calls, but check `config/cache.php`.

**2. Blade `@endauth` / `@endguest` added** — No breaking change.

**3. `$casts` preferred over `$dates`** — The `User` model uses neither, so no change needed.

### Verification
```bash
php artisan optimize:clear
php artisan route:list
php artisan serve
```

---

## Step 5 — Laravel 5.8 → 6.x

### PHP requirement: PHP 7.2+
> Upgrade your XAMPP PHP from 5.6/7.x to PHP 7.2 before this step.

### Composer command
```bash
composer require laravel/framework:^6.0 --no-update
composer require laravel/tinker:^1.0 --no-update
composer update
```

### Breaking changes affecting THIS codebase

**1. Global string/array helper functions REMOVED**  
The following helpers no longer exist as global functions. Replace each one:

| Old (removed) | New |
|---|---|
| `array_add()` | `Arr::add()` |
| `array_collapse()` | `Arr::collapse()` |
| `array_divide()` | `Arr::divide()` |
| `array_dot()` | `Arr::dot()` |
| `array_except()` | `Arr::except()` |
| `array_first()` | `Arr::first()` |
| `array_flatten()` | `Arr::flatten()` |
| `array_forget()` | `Arr::forget()` |
| `array_get()` | `Arr::get()` |
| `array_has()` | `Arr::has()` |
| `array_last()` | `Arr::last()` |
| `array_only()` | `Arr::only()` |
| `array_pluck()` | `Arr::pluck()` |
| `array_prepend()` | `Arr::prepend()` |
| `array_pull()` | `Arr::pull()` |
| `array_random()` | `Arr::random()` |
| `array_set()` | `Arr::set()` |
| `array_sort()` | `Arr::sort()` |
| `array_sort_recursive()` | `Arr::sortRecursive()` |
| `array_where()` | `Arr::where()` |
| `array_wrap()` | `Arr::wrap()` |
| `str_contains()` | `Str::contains()` |
| `str_finish()` | `Str::finish()` |
| `str_is()` | `Str::is()` |
| `str_limit()` | `Str::limit()` |
| `str_plural()` | `Str::plural()` |
| `str_random()` | `Str::random()` |
| `str_replace_array()` | `Str::replaceArray()` |
| `str_singular()` | `Str::singular()` |
| `str_slug()` | `Str::slug()` |
| `str_start()` | `Str::start()` |
| `camel_case()` | `Str::camel()` |
| `kebab_case()` | `Str::kebab()` |
| `snake_case()` | `Str::snake()` |
| `studly_case()` | `Str::studly()` |
| `title_case()` | `Str::title()` |

**Scan this project for usage:**
```bash
grep -r "array_add\|array_only\|str_plural\|str_slug\|camel_case\|snake_case\|title_case\|studly_case\|kebab_case\|str_contains\|str_limit\|str_random\|str_finish\|str_is\|str_start\|str_replace_array\|str_singular" app/ resources/
```
A scan of this codebase shows **no usages** of these deprecated helpers in PHP files. Blade views may need a separate scan.

**2. `Str` and `Arr` classes must be imported**  
Add `use Illuminate\Support\Str;` and `use Illuminate\Support\Arr;` at the top of any file using them.

**3. `laravel/tinker` now lives in its own package** — Already in `composer.json`.

### Verification
```bash
php artisan optimize:clear
php artisan route:list
php artisan serve
```
Run through all manual test checklist items.

---

## Step 6 — Laravel 6.x → 7.x

### PHP requirement: PHP 7.2.5+

### Composer command
```bash
composer require laravel/framework:^7.0 --no-update
composer require symfony/http-foundation:^5.0 --no-update
composer update
```

### Breaking changes affecting THIS codebase

**1. Symfony 5 components** — Laravel 7 uses Symfony 5 internally. No direct code change needed unless you use Symfony classes directly (this project does not).

**2. `Route::view()` and `Route::redirect()` changes** — Not used in this project.

**3. Blade component syntax** — Old `@component` directive still works. No change needed.

**4. `Model::getForeignKey()` renamed to `Model::getForeignKeyName()`** — Not used in this project (no Eloquent relationships defined).

**5. `assertExactJson()` in tests** — Not applicable (no tests).

**6. CORS support built-in** — Add `config/cors.php`:
```bash
php artisan vendor:publish --tag=cors
```

### Verification
```bash
php artisan optimize:clear
php artisan route:list
php artisan serve
```

---

## Step 7 — Laravel 7.x → 8.x ⚠️ MOST IMPACTFUL STEP

### PHP requirement: PHP 7.3+

### Composer command
```bash
composer require laravel/framework:^8.0 --no-update
composer require laravel/tinker:^2.0 --no-update
composer update
```

### Breaking changes affecting THIS codebase

**1. BREAKING — String-based route controller syntax REMOVED**  
**File:** `routes/web.php` — ALL 80+ routes  

Every route in this file uses the removed `'ControllerName@method'` string syntax. This is the single largest change in the entire upgrade.

**Before (will throw error):**
```php
Route::get('category', 'category@index')->name('category');
Route::post('addcategory', 'category@insert');
```

**After (required):**
```php
use App\Http\Controllers\category;
Route::get('category', [category::class, 'index'])->name('category');
Route::post('addcategory', [category::class, 'insert']);
```

You must update **every single route** in `routes/web.php`. Add all `use` imports at the top of the file. This is ~80 lines of changes.

**2. BREAKING — `$namespace` property in RouteServiceProvider REMOVED**  
**File:** `app/Providers/RouteServiceProvider.php`

Remove line 17 (`protected $namespace`) and line 55 (`->namespace($this->namespace)`):

```php
// REMOVE this property:
protected $namespace = 'App\Http\Controllers';

// REMOVE this call in mapWebRoutes():
->namespace($this->namespace)

// REMOVE this call in mapApiRoutes():
->namespace($this->namespace)
```

**3. BREAKING — `CheckForMaintenanceMode` renamed**  
**File:** `app/Http/Kernel.php` line 17

```php
// REMOVE:
\Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
// ADD:
\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
```

**4. BREAKING — `bindings` middleware alias removed**  
**File:** `app/Http/Kernel.php` line 41

```php
// REMOVE:
'bindings',
// ADD:
\Illuminate\Routing\Middleware\SubstituteBindings::class,
```

**5. Models now in `App\Models` namespace by default**  
The existing models are in `App\` namespace. They will still work, but the convention changed. You may leave them in `App\` for now and move them in a later cleanup step.

**6. Replace `fzaninotto/faker` with `fakerphp/faker`**  
```bash
composer remove fzaninotto/faker
composer require --dev fakerphp/faker:^1.23
```
Update `database/seeds/UsersTablesSeeder.php`:
```php
// Before:
use Faker\Factory as Faker;
// After: same — the namespace is identical in fakerphp/faker
```

**7. Update `phpunit/phpunit`**
```bash
composer require --dev phpunit/phpunit:^9.0
```
Update `phpunit.xml` — the format changed. Run `php artisan test` and fix any test failures.

**8. Blade `@endif` and component changes** — The old `@component`/`@slot` syntax is still supported. No change needed.

### Verification
```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:list   # Must show all routes without errors
php artisan serve
```
Run all manual test checklist items. This is the most likely step to surface errors — allocate extra time.

---

## Step 8 — Laravel 8.x → 9.x

### PHP requirement: PHP 8.0+
> Upgrade PHP to 8.0 before this step.

### Composer command
```bash
composer require laravel/framework:^9.0 --no-update
composer require laravel/tinker:^2.7 --no-update
composer require --dev phpunit/phpunit:^9.5 --no-update
composer update
```

### Breaking changes affecting THIS codebase

**1. PHP 8.0 — Deprecation warnings become stricter**  
PHP 8.0 converts many previously silent errors into notices/warnings. Run the application and check `storage/logs/laravel.log` for new deprecation messages.

**2. Symfony 6 components** — Laravel 9 uses Symfony 6. No direct code change for this project.

**3. `str()` helper added** — New global helper, no conflict with existing code.

**4. `$schedule->timezone()` signature changed** — Not used in this project (console commands not implemented).

**5. `Illuminate\Http\Testing\File` class changes** — Not used (no tests).

**6. `Arr::join()` added** — Additive, no breaking change.

**7. PHP minimum check** — Ensure all `$_REQUEST`/`$_POST`/`$_GET` superglobal reads still work in PHP 8.0. They do, but any `null` value access will generate a TypeError where PHP 7 silently returned `null`. Wrap risky `$_REQUEST` reads with `$_REQUEST['key'] ?? ''`.

**In `app/Http/Controllers/web_api.php`** (lines 36–314), every `$_REQUEST['key']` that may be absent will now throw a PHP 8 TypeError. Fix pattern:
```php
// Before:
$name = $_REQUEST['name'];
// After (safe for PHP 8):
$name = $_REQUEST['name'] ?? '';
```

### Verification
```bash
php artisan optimize:clear
php artisan route:list
php artisan serve
```
Check `storage/logs/laravel.log` for PHP 8 deprecations.

---

## Step 9 — Laravel 9.x → 10.x

### PHP requirement: PHP 8.1+
> Upgrade PHP to 8.1 before this step.

### Composer command
```bash
composer require laravel/framework:^10.0 --no-update
composer require laravel/tinker:^2.8 --no-update
composer require --dev phpunit/phpunit:^10.0 --no-update
composer update
```

### Breaking changes affecting THIS codebase

**1. PHP 8.1 — `never` return type for functions that always throw**  
No change needed in this project.

**2. PHP 8.1 — Enums added** — Additive, no breaking change.

**3. Removed deprecated `Illuminate\Http\Request::getContent()` wrapping** — Not used.

**4. `Stringable` interface changes** — Not used directly.

**5. `mockery/mockery` must be `^1.5`**
```bash
composer require --dev mockery/mockery:^1.5
```

**6. Laravel 10 requires return types on stub files** — If you have published stubs and generated controllers via `artisan make:controller`, they now include return type hints. Existing controllers are not affected.

**7. PHPUnit 10 changes** — If you write tests, `setUp()` must now have `: void` return type. No tests currently exist in this project.

### Verification
```bash
php artisan optimize:clear
php artisan route:list
php artisan test
php artisan serve
```

---

## Step 10 — Laravel 10.x → 11.x ⚠️ SECOND MAJOR STEP

### PHP requirement: PHP 8.2+
> Upgrade PHP to 8.2 before this step.

### Composer command
```bash
composer require laravel/framework:^11.0 --no-update
composer require laravel/tinker:^2.9 --no-update
composer require --dev phpunit/phpunit:^11.0 --no-update
composer require --dev fakerphp/faker:^1.23 --no-update
composer update
```

### Breaking changes affecting THIS codebase

**1. BREAKING — `app/Http/Kernel.php` is removed in Laravel 11**  
The entire `app/Http/Kernel.php` file no longer exists in the new skeleton. Middleware is now registered in `bootstrap/app.php`.

**Action:** Create a new `bootstrap/app.php` based on the Laravel 11 skeleton and migrate all middleware from `Kernel.php`:

```php
// bootstrap/app.php (new L11 style)
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\VerifyCsrfToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
```

**2. BREAKING — `app/Console/Kernel.php` is removed in Laravel 11**  
Scheduled commands move to `routes/console.php`. This project has no scheduled commands — simply delete `app/Console/Kernel.php`.

**3. BREAKING — `RouteServiceProvider` no longer registered by default**  
The `RouteServiceProvider` is removed from the skeleton. Routes are now loaded directly via `bootstrap/app.php` (see above). Delete `app/Providers/RouteServiceProvider.php` and ensure `bootstrap/app.php` loads the route files.

**4. BREAKING — `AppServiceProvider` replaces most of the other providers**  
Review `app/Providers/AppServiceProvider.php`. The `Schema::defaultStringLength(191)` call in `boot()` should remain (still needed for older MySQL/MariaDB setups with utf8mb4).

**5. New directory structure — `app/Models/`**  
Move models from `app/` to `app/Models/`:
- `app/User.php` → `app/Models/User.php` (update namespace to `App\Models`)
- `app/category.php` → `app/Models/Category.php`
- `app/product.php` → `app/Models/Product.php`
- `app/slider.php` → `app/Models/Slider.php`
- `app/store.php` → `app/Models/Store.php`

Update `config/auth.php`:
```php
'model' => App\Models\User::class,
```

Update `app/Http/Controllers/system_user.php` line 9:
```php
// Before:
use App\User;
// After:
use App\Models\User;
```

**6. PHP 8.2 — Dynamic Properties Deprecation (this project: low impact)**  
`DB::table()` returns `stdClass` objects; dynamic properties on `stdClass` are still allowed in PHP 8.2. The pattern in `sub_category.php:82`, `sub_category_1.php:88`, `family.php:87` (`$category->sub = $s`) is safe.

**7. `config/app.php` — Remove `'log'` key, add new format**  
Laravel 11 reorganized `config/app.php`. Remove the deprecated `'log'` key. Keep your `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_DEBUG`, `APP_URL` settings.

**8. Vite instead of Laravel Mix (recommended, optional)**  
Laravel 11 defaults to Vite. To migrate from Mix:
```bash
npm remove laravel-mix
npm install --save-dev vite laravel-vite-plugin
```
Create `vite.config.js`:
```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
export default defineConfig({
    plugins: [
        laravel({ input: ['resources/assets/sass/app.scss', 'resources/assets/js/app.js'], refresh: true }),
    ],
});
```
In Blade views, replace `{{ mix('...') }}` calls with `@vite(...)`. This project's views use static asset references (`/public/...` paths), so this is lower priority.

### Verification
```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:list   # Must list all routes without errors
php artisan test
php artisan serve
```
Run the complete manual test checklist. Check `storage/logs/laravel.log` for any remaining deprecation warnings.

---

## Post-Upgrade Cleanup (After Laravel 11 is Stable)

These are not part of the mechanical upgrade but should be done immediately after:

### C1 — Fix all security issues from the audit
1. Wrap all admin routes in `middleware(['auth'])` in `routes/web.php`
2. Add role-based middleware (Gate or `spatie/laravel-permission`)
3. Hash passwords in `web_api.php` with `Hash::make()` / `Hash::check()`
4. Replace hardcoded verification codes `'123'` with `Str::random(6)`
5. Move hardcoded emails from `website.php` to `.env`
6. Replace all `$_REQUEST`/`$_POST`/`$_GET` with `$request->input()` and validation

### C2 — Fix the missing controllers / routes
1. Create `app/Http/Controllers/OrderController.php` (routes reference `order@index` etc.)
2. Add `check_validity()` and `check_validity1()` methods to `ProductController`

### C3 — Rename controllers to PSR-1 StudlyCaps
```
category        → CategoryController
product         → ProductController
slider          → SliderController
... (all 16 controllers)
```

### C4 — Define `$fillable` on all Models
```php
class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['english_name', 'logo', 'background'];
}
```

### C5 — Write Feature Tests
Target: at least smoke tests for the public website routes and admin CRUD operations. Aim for >80% coverage.
```bash
php artisan make:test CategoryTest
php artisan make:test WebsiteTest
```
