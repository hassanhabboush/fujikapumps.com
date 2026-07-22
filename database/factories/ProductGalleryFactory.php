<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductGallery>
 */
class ProductGalleryFactory extends Factory
{
    protected $model = ProductGallery::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'path'       => 'public/productimage/' . fake()->uuid() . '.jpg',
        ];
    }
}
