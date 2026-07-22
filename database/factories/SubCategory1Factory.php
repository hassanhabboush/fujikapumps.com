<?php

namespace Database\Factories;

use App\Models\SubCategory1;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SubCategory1>
 */
class SubCategory1Factory extends Factory
{
    protected $model = SubCategory1::class;

    public function definition(): array
    {
        return [
            'english_name' => fake()->unique()->words(2, true),
            'background'   => 'public/subcategory1uploads/' . fake()->uuid() . '.jpg',
        ];
    }
}
