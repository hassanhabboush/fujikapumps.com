<?php

namespace Database\Factories;

use App\Models\Family;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Family>
 */
class FamilyFactory extends Factory
{
    protected $model = Family::class;

    public function definition(): array
    {
        return [
            'english_name' => fake()->unique()->words(2, true),
            'background'   => 'public/categorybackground/' . fake()->uuid() . '.jpg',
            'link'         => 'https://example.test',
        ];
    }
}
