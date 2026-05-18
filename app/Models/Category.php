<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;

class Category extends Model
{
    use HasMediaUrls;

    protected $table = 'categories';

    protected $guarded = [];

    // protected array $mediaFields = ['logo', 'background'];

    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'category_subcategory', 'category_id', 'subcategory_id');
    }
}
