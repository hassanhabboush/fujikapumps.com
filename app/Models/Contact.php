<?php

namespace App\Models;

use App\Traits\InvalidatesCache;
use App\Support\CatalogCache;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use InvalidatesCache;

    protected $table = 'contact';

    protected $guarded = [];

    protected array $cacheKeys = ['contact', CatalogCache::VERSION_KEY];
}
