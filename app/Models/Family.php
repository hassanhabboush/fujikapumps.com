<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use Illuminate\Database\Eloquent\Model;
use App\Models\Series;
use App\Models\SubCategory1;

class Family extends Model
{
    use HasMediaUrls;
    protected $table = 'family';
    protected $guarded = [];


    public function series()
    {
        return $this->hasMany(Series::class);
    }

    public function subCategory1s()
    {
        return $this->belongsToMany(SubCategory1::class, 'family_subcategory', 'family_id', 'sub_category_id');
    }
}
