<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    use HasFactory, HasMediaUrls;
    protected $table = 'product_gallery';

    public $timestamps = false;

    protected $guarded = [];
}
