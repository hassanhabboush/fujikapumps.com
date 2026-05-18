<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\SubCategory1;

class SubCategory extends Model
{
    use HasMediaUrls;
    protected $table = 'sub_category';
    protected $guarded = [];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_subcategory', 'subcategory_id', 'category_id');
    }

    public function subCategory1s()
    {
        return $this->belongsToMany(SubCategory1::class, 'subcategory_subcategory', 'parent_id', 'subcategory_id');
    }
}
