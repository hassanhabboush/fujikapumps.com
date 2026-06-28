<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use App\Traits\InvalidatesCache;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;

class Category extends Model
{
    use HasMediaUrls, InvalidatesCache;

    protected $table = 'categories';

    protected $guarded = [];

    protected array $cacheKeys = ['headerCategories'];

    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'category_subcategory', 'category_id', 'subcategory_id');
    }
}
