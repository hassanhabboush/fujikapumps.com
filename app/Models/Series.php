<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\CatalogCache;
use App\Support\AdminSidebarCounts;
use App\Traits\InvalidatesCache;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory, HasMediaUrls, InvalidatesCache;

    protected $table = 'series';

    public $timestamps = false;

    protected $guarded = [];

    protected array $cacheKeys = [CatalogCache::VERSION_KEY, AdminSidebarCounts::CACHE_KEY];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}
