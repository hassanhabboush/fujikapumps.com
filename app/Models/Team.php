<?php

namespace App\Models;

use App\Traits\HasMediaUrls;
use App\Support\CatalogCache;
use App\Traits\InvalidatesCache;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasMediaUrls, InvalidatesCache;
    protected $table = 'team';

    public $timestamps = false;

    protected $guarded = [];

    protected array $cacheKeys = [CatalogCache::VERSION_KEY];
}
