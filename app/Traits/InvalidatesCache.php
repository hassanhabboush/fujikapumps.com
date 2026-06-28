<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

/**
 * Forgets only the cache keys a model is responsible for on save/delete,
 * instead of flushing the entire cache.
 *
 * Each model using this trait must declare the keys it invalidates:
 *
 *     protected array $cacheKeys = ['headerCategories'];
 */
trait InvalidatesCache
{
    public static function bootInvalidatesCache(): void
    {
        static::saved(fn ($model) => $model->forgetCacheKeys());
        static::deleted(fn ($model) => $model->forgetCacheKeys());
    }

    protected function forgetCacheKeys(): void
    {
        foreach ($this->cacheKeys ?? [] as $key) {
            Cache::forget($key);
        }
    }
}
