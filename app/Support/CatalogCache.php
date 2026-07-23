<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Namespaced cache for the public catalog.
 *
 * Two problems this solves. First, the public site and the admin panel used to
 * cache different shapes under the same bare keys ('categories', 'accessories',
 * 'gallery', 'about') — whichever request came first won for an hour. Every
 * public key now lives under a 'catalog.' prefix so the two can never collide.
 *
 * Second, the file driver has no tags, so per-id keys (one product, the series
 * of one sub-category) could never be enumerated for invalidation. The prefix
 * carries a version stamp: forgetting the stamp orphans the whole namespace at
 * once, and the stale entries fall out on their own TTL. Catalog models list
 * VERSION_KEY in their $cacheKeys so InvalidatesCache bumps it on save/delete.
 */
class CatalogCache
{
    public const TTL = 3600;

    public const VERSION_KEY = 'catalog_version';

    public static function remember(string $key, Closure $callback): mixed
    {
        return Cache::remember(self::qualify($key), self::TTL, $callback);
    }

    public static function qualify(string $key): string
    {
        return 'catalog.' . self::version() . '.' . $key;
    }

    public static function version(): string
    {
        return Cache::rememberForever(self::VERSION_KEY, fn (): string => (string) Str::uuid());
    }

    /**
     * Invalidate every catalog key at once by moving the namespace.
     */
    public static function bump(): void
    {
        Cache::forget(self::VERSION_KEY);
    }
}
