<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use App\Support\CatalogCache;
use App\Traits\InvalidatesCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory, HasMediaUrls, InvalidatesCache;

    protected $table = 'accessories';

    public $timestamps = false;

    protected $guarded = [];

    protected array $cacheKeys = ['accessories', CatalogCache::VERSION_KEY];
}
