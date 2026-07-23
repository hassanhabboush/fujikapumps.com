<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use App\Support\CatalogCache;
use App\Traits\InvalidatesCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\SubCategory1;

class SubCategory extends Model
{
    use HasFactory, HasMediaUrls, InvalidatesCache;
    protected $table = 'sub_category';
    protected $guarded = [];

    protected array $cacheKeys = ['headerCategories', CatalogCache::VERSION_KEY];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_subcategory', 'subcategory_id', 'category_id');
    }

    public function subCategory1s()
    {
        return $this->belongsToMany(SubCategory1::class, 'subcategory_subcategory', 'parent_id', 'subcategory_id');
    }
}
