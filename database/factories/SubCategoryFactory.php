<?php

namespace Database\Factories;

use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SubCategory>
 */
class SubCategoryFactory extends Factory
{
    protected $model = SubCategory::class;

    public function definition(): array
    {
        return [
            'english_name' => fake()->unique()->words(2, true),
            'background'   => 'public/categorybackground/' . fake()->uuid() . '.jpg',
        ];
    }
}
