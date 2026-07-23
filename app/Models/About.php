<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use App\Support\CatalogCache;
use App\Traits\InvalidatesCache;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasMediaUrls, InvalidatesCache;
    protected $table = 'about';
    protected $guarded = [];

    protected array $cacheKeys = [CatalogCache::VERSION_KEY];
}
