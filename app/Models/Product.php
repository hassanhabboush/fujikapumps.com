<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\CatalogCache;
use App\Traits\InvalidatesCache;
use Illuminate\Database\Eloquent\Model;
use App\Models\Family;
use App\Models\ProductParameter;
use App\Models\Category;
use App\Models\SubCategory;

class Product extends Model
{
    use HasFactory, HasMediaUrls, InvalidatesCache;
    protected $table = 'products';

    protected $guarded = [];

    protected array $cacheKeys = [CatalogCache::VERSION_KEY];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function parameters()
    {
        return $this->hasMany(ProductParameter::class, 'product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id');
    }

    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'product_subcategory', 'product_id', 'sub_category_id');
    }

    public function store()
    {
         return $this->belongsTo(Store::class);
    }

}
