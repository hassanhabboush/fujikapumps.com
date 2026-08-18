<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use App\Support\CatalogCache;
use App\Support\AdminSidebarCounts;
use App\Traits\InvalidatesCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Series;
use App\Models\SubCategory1;

class Family extends Model
{
    use HasFactory, HasMediaUrls, InvalidatesCache;
    protected $table = 'family';
    protected $guarded = [];

    protected array $cacheKeys = ['headerCategories', 'families', CatalogCache::VERSION_KEY, AdminSidebarCounts::CACHE_KEY];

    public function series()
    {
        return $this->hasMany(Series::class);
    }

    public function subCategory1s()
    {
        return $this->belongsToMany(SubCategory1::class, 'family_subcategory', 'family_id', 'sub_category_id');
    }
}
