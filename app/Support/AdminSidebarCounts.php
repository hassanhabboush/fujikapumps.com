<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Badge counts for the admin sidebar. Nine COUNT queries on every HTML page
 * made the whole panel wait on the database; one cached row is enough until
 * a catalog model saves and forgets this key.
 */
class AdminSidebarCounts
{
    public const CACHE_KEY = 'admin.sidebar.counts';

    /**
     * @return array{
     *     categories: int,
     *     sub_category: int,
     *     sub_category_1: int,
     *     family: int,
     *     series: int,
     *     accessories: int,
     *     products: int,
     *     featured: int,
     *     slider: int
     * }
     */
    public static function all(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addMinutes(10), function (): array {
            return [
                'categories'     => (int) DB::table('categories')->count(),
                'sub_category'   => (int) DB::table('sub_category')->count(),
                'sub_category_1' => (int) DB::table('sub_category_1')->count(),
                'family'         => (int) DB::table('family')->count(),
                'series'         => (int) DB::table('series')->count(),
                'accessories'    => (int) DB::table('accessories')->count(),
                'products'       => (int) DB::table('products')->count(),
                'featured'       => (int) DB::table('products')->where('is_featured', 1)->count(),
                'slider'         => (int) DB::table('slider')->count(),
            ];
        });
    }
}
