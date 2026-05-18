<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;
use App\Models\Family;

class SubCategory1 extends Model
{
    use HasMediaUrls;
    protected $table = 'sub_category_1';

    protected $guarded = [];

    public function parentSubCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'subcategory_subcategory', 'subcategory_id', 'parent_id');
    }

    public function families()
    {
        return $this->belongsToMany(Family::class, 'family_subcategory', 'sub_category_id', 'family_id');
    }
}
