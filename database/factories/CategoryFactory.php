<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'english_name' => fake()->unique()->words(2, true),
            // `logo` is NOT NULL without a default in the original schema.
            'logo'         => '',
            'background'   => 'public/categorybackground/' . fake()->uuid() . '.jpg',
        ];
    }
}
