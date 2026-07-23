<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use App\Support\CatalogCache;
use App\Traits\InvalidatesCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;
use App\Models\Family;

class SubCategory1 extends Model
{
    use HasFactory, HasMediaUrls, InvalidatesCache;
    protected $table = 'sub_category_1';

    protected $guarded = [];

    protected array $cacheKeys = ['headerCategories', CatalogCache::VERSION_KEY];

    public function parentSubCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'subcategory_subcategory', 'subcategory_id', 'parent_id');
    }

    public function families()
    {
        return $this->belongsToMany(Family::class, 'family_subcategory', 'sub_category_id', 'family_id');
    }
}
