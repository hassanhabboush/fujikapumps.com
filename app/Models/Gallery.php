<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use App\Support\CatalogCache;
use App\Traits\InvalidatesCache;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasMediaUrls, InvalidatesCache;
    protected $table = 'gallery';

    public $timestamps = false;

    protected $guarded = [];

    protected array $cacheKeys = [CatalogCache::VERSION_KEY];
}
