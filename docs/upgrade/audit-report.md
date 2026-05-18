# Fujika Industries — Code Audit Report
**Date:** 2026-04-26  
**Auditor:** Ahmed Achfakay (devdreame@gmail.com)
**Project:** `c:\xampp\htdocs\fujika.local`  
**Framework:** Laravel 5.4 | PHP ≥ 5.6.4 | MySQL

---

## 1. N+1 Query Problems

> Note: The project uses only `DB::table()` (Query Builder), not Eloquent models with relationships, so classic Eloquent N+1 via lazy-loaded relations does not apply. However, the equivalent anti-pattern — running database queries inside loops — is present in multiple places.

### 1.1 `web_api.php` — `order_history()` — N+1 inside foreach

**File:** `app/Http/Controllers/web_api.php`  
**Lines:** 470–507  
**Problematic code:**
```php
foreach($orders as $r) {
    // Line 480 — a full JOIN query per order row
    $tmp1['order_items'] = DB::table('products')
        ->join('order_items', 'order_items.product_id', '=', 'products.id')
        ->join('stores', 'stores.id', '=', 'products.store_id')
        ->select(...)
        ->where('order_items.order_id','=',$r->id)->get();
    
    // Line 482 — another query per order row if promo_id is set
    $promos = DB::select(DB::raw("select * from promos where promo_id = :promop"), ...);
}
```
**Impact:** For N orders, this fires up to 2N+1 queries.  
**Fix:** Collect all order IDs first, then use a single `whereIn` query and group results in PHP:
```php
$orderIds = $orders->pluck('id');
$allItems = DB::table('products')
    ->join('order_items', 'order_items.product_id', '=', 'products.id')
    ->join('stores', 'stores.id', '=', 'products.store_id')
    ->select('order_items.order_id', 'order_items.id', ...)
    ->whereIn('order_items.order_id', $orderIds)->get()
    ->groupBy('order_id');
// Then map: $tmp1['order_items'] = $allItems[$r->id] ?? collect();
```

---

### 1.2 `web_api.php` — `reserve_order()` — query inside foreach

**File:** `app/Http/Controllers/web_api.php`  
**Lines:** 388–402  
**Problematic code:**
```php
foreach($card_priceArr as $v) {
    $card_priceArrInner = explode(',', $v);
    // Line 392 — one SELECT per item
    $pp = DB::table('products')->where('id', $card_priceArrInner[0])->first();
    // Line 400 — one UPDATE per item
    DB::table('products')->where([['id','=',$card_priceArrInner[0]]])->decrement('quantity', $card_priceArrInner[1]);
}
```
**Impact:** For N cart items, fires 2N queries.  
**Fix:** Pre-load all product IDs with a single `whereIn`, then loop over the in-memory collection.

---

### 1.3 `web_api.php` — `check_expired_tmp_orders()` — 3 queries per row in loop

**File:** `app/Http/Controllers/web_api.php`  
**Lines:** 565–571  
**Problematic code:**
```php
$m = DB::select(DB::raw("SELECT ... FROM temp_order_items WHERE TIMESTAMPDIFF(MINUTE, created_at, NOW()) > 10"), []);
foreach($m as $r) {
    DB::table('products')->where([['id','=',$r->product_id]])->increment('quantity', $r->quantity); // per row
    DB::table('temp_order_items')->where('id', '=', $r->id)->delete();                             // per row
    DB::table('temp_order')->where('tmp_order_id', '=', $r->temp_order_id)->delete();              // per row
}
```
**Impact:** For N expired items, fires 3N queries.  
**Fix:** Use bulk operations — collect IDs, use `whereIn` for deletes, and update quantities in a single case-based update or loop with chunking.

---

### 1.4 `website.php` — `filter()` — 2 queries per result row in for loop

**File:** `app/Http/Controllers/website.php`  
**Lines:** 274–279  
**Problematic code:**
```php
$productsarr = $products->get();
for ($i = 0; $i < count($productsarr); $i++) {
    // Line 277 — one query per product
    $productsarr[$i]->q = DB::table('product_parameter')->select('q')->orderBy('q','DESC')
        ->where('product_parameter.Model', $productsarr[$i]->Model)->get()[0]->q;
    // Line 278 — one query per product
    $productsarr[$i]->h = DB::table('product_parameter')->select('h')->orderBy('h','DESC')
        ->where('product_parameter.Model', $productsarr[$i]->Model)->get()[0]->h;
}
```
**Impact:** For N filtered products, fires 2N additional queries on top of the main query.  
**Fix:** Use a subquery or a single `selectRaw` with `MAX(q)` and `MAX(h)` grouped by Model in the primary query.

---

## 2. Security Issues

### 2.1 CRITICAL — Plaintext Password Storage and Comparison

**File:** `app/Http/Controllers/web_api.php`  
**Lines:** 52–58 (insert), 278 (login comparison), 349 (update)

```php
// Line 52 — stored in plaintext
'account_password' => $account_password,

// Line 278 — compared in plaintext
->where([['email','=',$email], ['account_password','=', $account_password]])->get();

// Line 349 — updated in plaintext
'account_password' => $new_password
```
**Severity:** CRITICAL — User passwords are stored and compared in plaintext. Any database breach exposes all user credentials.  
**Fix:** Use `Hash::make()` on insert/update; use `Hash::check()` on login. Requires a migration to re-hash all existing passwords (requires forcing a password reset).

---

### 2.2 CRITICAL — No Authentication Middleware on Admin Routes

**File:** `routes/web.php`  
**Lines:** 13–314

The entire admin panel — categories, products, sliders, users, orders — is accessible without any authentication. Only the `login` route checks credentials. There is no `auth` middleware wrapping any admin route.

```php
// Example — line 23 — publicly accessible:
Route::get('system_user', 'system_user@index');
Route::post('addsystem_user', 'system_user@insert');
Route::get('deletesystem_user', 'system_user@delete');
```
**Fix:** Wrap all admin routes in `Route::middleware(['auth'])`. In Laravel 8+, also add `verified` if email verification is used.

---

### 2.3 HIGH — Hardcoded Email Addresses in Controller

**File:** `app/Http/Controllers/website.php`  
**Lines:** 134–146

```php
$to_email = "sales@fujikaindustries.com";
$message->from("FujikaIndustry@gmail.com", "Fujika Contact Form");
```
**Fix:** Move to `.env` (`MAIL_TO_ADDRESS`, `MAIL_FROM_ADDRESS`) and read via `config('mail.from.address')`.

---

### 2.4 HIGH — Hardcoded Verification Codes

**File:** `app/Http/Controllers/web_api.php`  
**Lines:** 146, 148, 153, 157, 159, 179, 189

```php
'email_code' => '123',
'sms_code'   => '123',
```
All verification codes are hardcoded to `'123'`. Any user can verify any account by supplying `123`.  
**Fix:** Generate random codes with `Str::random(6)` or `rand(100000, 999999)`, store temporarily, and send via proper email/SMS.

---

### 2.5 HIGH — Mass Unvalidated `$_REQUEST` Input

**File:** `app/Http/Controllers/web_api.php` (entire file, e.g. lines 36–79, 82–166, 252–302, 305–337, 339–358)  
**File:** `app/Http/Controllers/website.php` (lines 136–141)  
**File:** Multiple admin controllers (`category.php`, `sub_category.php`, `family.php`, `series.php`, etc.)

All controller methods read directly from `$_REQUEST`, `$_POST`, `$_GET` superglobals with no validation, sanitization, or type-casting. This enables injection of unexpected data types, oversized payloads, and arbitrary field injection into `DB::table()->update()` calls.

**Fix:** Replace all `$_REQUEST`/`$_POST`/`$_GET` with `$request->validated()` after defining a `FormRequest` class with rules, or at minimum `$request->input('field')` with inline validation.

---

### 2.6 MEDIUM — Commented-Out SQL Injection Vulnerable Query Retained in Codebase

**File:** `app/Http/Controllers/web_api.php`  
**Line:** 19

```php
// $stores = DB::select( DB::raw("select id,name,logo,cat_id from stores where ... and cat_id = '$category_id'") );
```
The active query (line 17) uses bindings correctly, but the injection-vulnerable version remains in comments and could accidentally be re-enabled.  
**Fix:** Delete the commented-out code.

---

### 2.7 MEDIUM — Role Check Absent — All Authenticated Users Can Access All Admin Functions

**File:** `app/Http/Kernel.php`, `routes/web.php`

The `users` table has a `role` column (1=Admin, 2=User B, 3=User C) but no role-based access middleware exists. Any authenticated admin-panel user can delete other users, change system settings, or access all resources regardless of role.  
**Fix:** Implement Laravel Gates or Policies, or use a package like `spatie/laravel-permission`.

---

### 2.8 MEDIUM — No Rate Limiting on Login or API Endpoints

**File:** `routes/web.php`, `app/Http/Kernel.php`

The login form (`POST checklogin`) has no rate limiting. The admin panel has no throttle middleware. The `web_api.php` endpoints (register, login, verify) also have no throttle.  
**Fix:** Apply `throttle:5,1` middleware to the login route and `throttle:60,1` to API endpoints.

---

### 2.9 LOW — Session-Based Password Caching

**File:** `app/Http/Controllers/system_user.php`  
**Lines:** 61, 115

```php
// Line 115 — hashed password stored in session
Session::put('system_userpass', $users[0]->password);

// Line 61 — edit only updates password if it changed vs session value
if ($password == Session::get('system_userpass')) { ... }
```
Storing password hashes in session is a bad practice. The comparison logic can also behave incorrectly across sessions.  
**Fix:** Remove this pattern; compare with `Hash::check()` directly.

---

## 3. Deprecated Code

### 3.1 Laravel 8 — String-Based Controller Routing Syntax Removed

**File:** `routes/web.php`  
**Lines:** 23–311 (all routes)

```php
// Laravel 5.4 syntax — REMOVED in Laravel 8
Route::get('system_user', 'system_user@index');
Route::post('addsystem_user', 'system_user@insert');
// ... (all 80+ routes use this syntax)
```
**Fix (Laravel 8+):** Use array syntax or FQCN:
```php
use App\Http\Controllers\system_user;
Route::get('system_user', [system_user::class, 'index']);
```

---

### 3.2 Laravel 8 — `$namespace` Property in RouteServiceProvider Removed

**File:** `app/Providers/RouteServiceProvider.php`  
**Line:** 17, 55

```php
protected $namespace = 'App\Http\Controllers'; // removed in L8
->namespace($this->namespace)                  // removed in L8
```
**Fix:** Remove the `$namespace` property and `->namespace()` calls. Use FQCN or `use` imports in route files.

---

### 3.3 Laravel 8 — `CheckForMaintenanceMode` Renamed

**File:** `app/Http/Kernel.php`  
**Line:** 17

```php
\Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
```
**Fix (Laravel 8+):** Replace with:
```php
\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
```

---

### 3.4 Laravel 8 — `bindings` Middleware Alias Removed

**File:** `app/Http/Kernel.php`  
**Line:** 41

```php
'api' => [
    'throttle:60,1',
    'bindings', // removed in L8
],
```
**Fix:** Replace `'bindings'` with `\Illuminate\Routing\Middleware\SubstituteBindings::class`.

---

### 3.5 Laravel 11 — `app/Http/Kernel.php` and `app/Console/Kernel.php` Removed

**File:** `app/Http/Kernel.php` (entire file)

Laravel 11 eliminates the HTTP Kernel class entirely. Middleware registration moves to `bootstrap/app.php`. The `$middleware`, `$middlewareGroups`, and `$routeMiddleware` arrays are replaced by a fluent `withMiddleware()` API.  
**Fix:** Migrate all middleware registration to the new `bootstrap/app.php` pattern.

---

### 3.6 PHP 8.0 — Named Arguments and Match Expression (Opportunity)

**File:** Multiple controllers

The codebase has long chains of `if/elseif` that can be simplified with `match()` in PHP 8.0+. This is not a breaking issue but a deprecation opportunity.

---

### 3.7 PHP 8.2 — Dynamic Properties Deprecated → Error in PHP 9

**File:** `app/Http/Controllers/sub_category.php`, `sub_category_1.php`, `family.php`  
**Lines:** `sub_category.php:82`, `sub_category_1.php:88`, `family.php:87`

```php
// sub_category.php line 82 — dynamic property on stdClass from DB::table() result
$category->sub = $s;
```
Adding dynamic properties to `stdClass` returned by `DB::table()` is fine in PHP 8.2 (it only affects user-defined classes), but if models are later converted to Eloquent Model classes, this would generate deprecation warnings.  
**Fix:** Cast results to arrays and add the key, or use `array_merge`.

---

### 3.8 Laravel 5.4 — `array_add`, `str_plural` and Other Global Helpers

These Laravel global helper functions (`array_add`, `array_only`, `array_except`, `str_plural`, `str_singular`, `camel_case`, `snake_case`, `title_case`) were removed in Laravel 6. A scan of the views and controllers found no usage of these specific helpers, so **no action required** for this specific sub-item.

---

### 3.9 `fzaninotto/faker` — ABANDONED Package

**File:** `composer.json`  
**Line:** 13

```json
"fzaninotto/faker": "~1.4"
```
This package is **officially abandoned** and has no PHP 8.2 support.  
**Fix:** Replace with `"fakerphp/faker": "^1.23"`.

---

### 3.10 Laravel Mix v1 — Incompatible with Modern Tooling

**File:** `webpack.mix.js`, `package.json`

Laravel Mix v1 (used in Laravel 5.4) requires Webpack 3 and Node.js <12. It is incompatible with modern Node.js (18+/20+). Laravel 11 uses Vite by default.  
**Fix:** Either upgrade Laravel Mix to v6, or migrate the asset pipeline to Vite (recommended for L11).

---

## 4. Code Quality Issues

### 4.1 Eloquent Models Missing `$fillable` / `$guarded`

**Files:** `app/category.php`, `app/product.php`, `app/slider.php`, `app/store.php`

All four non-User models are completely empty stubs with no `$fillable`, `$guarded`, `$table`, or any relationships defined. Mass assignment protection is neither explicitly allowed nor denied.

```php
class category extends Model
{
    // empty — no $fillable, no $guarded, no $table
}
```
**Fix:** At minimum add `protected $guarded = [];` or define explicit `$fillable` arrays. Also define `$table` since the table name `categories` does not follow Laravel's default convention for the class name `category`.

---

### 4.2 No Return Type Hints on Any Controller Method

**Files:** All controllers (18 files)

Zero methods across all controllers have PHP return type declarations. This makes static analysis, IDE assistance, and future refactoring significantly harder.

**Fix:** Add return types progressively: `public function index(): View`, `public function delete(): RedirectResponse`, etc.

---

### 4.3 Direct `env()` Calls Outside Config Files

**File:** Not found in controllers — `env()` is not directly called in PHP controllers.  
**File:** `config/app.php`, `config/database.php`, etc. — all use `env()` correctly inside config files.  
**Status:** No violation found here.

---

### 4.4 Massive Route Duplication

**File:** `routes/web.php`  
**Lines:** 13–126 (inside `admin` prefix group) and 129–314 (outside the group)

The **same routes are defined twice** — once inside the `prefix('admin')` group and again at the root level. This means every endpoint is accessible at both `/admin/category` and `/category`. The duplicate set outside the prefix group has no auth middleware either.

```php
// Lines 34–39 — inside admin group
Route::get('category', 'category@index')->name('category');
// ...
// Lines 130–135 — outside admin group, DUPLICATE
Route::get('category', 'category@index')->name('category');
```
**Fix:** Remove the duplicate route block (lines 129–314 or 13–126, whichever set is intended), and apply auth middleware to the remaining set.

---

### 4.5 Inconsistent Controller Naming (PSR-4 Violation)

**Files:** All controllers

All controllers use lowercase class names (`class category`, `class product`, `class slider`, etc.) which violates PSR-1 (class names must be in `StudlyCaps`). The `app\Http\Controllers\.php` filename is unnamed (a dot-file containing the `currency` class), which will not autoload correctly under PSR-4.

**Fix:** Rename all controller classes and files to `StudlyCaps`: `CategoryController`, `ProductController`, etc.

---

### 4.6 Superglobal Abuse — `$_POST`, `$_GET`, `$_REQUEST` Throughout

**Files:** All admin controllers (every `insert()` and `edit()` method), `web_api.php`

Laravel's `Request` object is injected but largely ignored. Raw superglobals are used instead, bypassing middleware, request lifecycle, type safety, and making testing impossible.

**Fix:** Replace all `$_POST['field']` → `$request->input('field')`, and add `FormRequest` validation classes.

---

### 4.7 `product.php` Controller — `check_validity` and `check_validity1` Routes Not in Controller

**File:** `routes/web.php` (lines 107–108), `app/Http/Controllers/product.php`

Routes reference `product@check_validity` and `product@check_validity1` but these methods do not exist in the `product` controller.  
**Status:** These routes will return a 500 error if called.

---

### 4.8 Missing `order` Controller

**File:** `routes/web.php`  
**Lines:** 231–239

```php
Route::get('order', 'order@index');
Route::get('readorder', 'order@readall');
// ... etc.
```
There is no `app/Http/Controllers/order.php` file.  
**Status:** All order admin routes will 500.

---

### 4.9 Migrations Are Severely Outdated

**Files:** `database/migrations/` (all 13 files)

The migration files define schemas that **do not match the actual live database** used by the application. For example:
- `Product` migration defines only: `store_id`, `quantity`, `name`, `logo`, `price`
- Actual `products` table (from controllers) has: `family_id`, `descreption`, `photo`, `is_featured`, `link`
- `Category` migration has no `background` or `short_descreption` columns
- `Slider` migration has only `image` and `text`, but the actual table has `text1`, `text2`, `text3`, `buttontext`, `buttonlink`

This means `php artisan migrate:fresh` will break the application.  
**Fix:** Before upgrading, reverse-engineer the current live database into fresh migration files using `php artisan schema:dump` (L8+) or a tool like `kitloong/laravel-migrations-generator`.

---

## 5. Package Compatibility with Laravel 11 / PHP 8.2

| Package | Current Version | L11 / PHP 8.2 Compatible | Recommended Replacement / Version |
|---|---|---|---|
| `laravel/framework` | `5.4.*` | ❌ No | `^11.0` |
| `laravel/tinker` | `~1.0` | ❌ No | `^2.9` |
| `fzaninotto/faker` | `~1.4` | ❌ No — **ABANDONED** | `fakerphp/faker ^1.23` |
| `mockery/mockery` | `0.9.*` | ❌ No | `^1.6` |
| `phpunit/phpunit` | `~5.7` | ❌ No | `^11.0` |

**All five packages require replacement.** There are no third-party application packages beyond the Laravel core stack, which simplifies the upgrade considerably.

---

## Summary Counts

| Category | Count |
|---|---|
| N+1 / Query-in-loop issues | 4 |
| Security issues | 9 |
| Deprecated / removed code items | 10 |
| Code quality issues | 9 |
| Incompatible packages | 5 |
| **Total issues** | **37** |
