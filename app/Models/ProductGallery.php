<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    use HasMediaUrls;
    protected $table = 'product_gallery';

    public $timestamps = false;

    protected $guarded = [];
}
