<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name'        => fake()->unique()->words(2, true),
            'photo'       => 'public/productuploads/' . fake()->uuid() . '.jpg',
            'descreption' => fake()->sentence(),
            'link'        => 'https://example.test',
            'family_id'   => Family::factory(),
            'quantity'    => 0,
            'is_featured' => 0,
        ];
    }
}
