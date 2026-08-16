<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\CatalogCache;
use App\Traits\InvalidatesCache;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductParameter extends Model
{
    use HasFactory, InvalidatesCache;

    protected $table = 'product_parameter';
    protected $guarded = [];

    protected array $cacheKeys = [CatalogCache::VERSION_KEY];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
