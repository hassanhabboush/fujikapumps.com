<?php

namespace Database\Factories;

use App\Models\Accessory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Accessory>
 */
class AccessoryFactory extends Factory
{
    protected $model = Accessory::class;

    public function definition(): array
    {
        return [
            'photo' => 'public/accessoriesuploads/' . fake()->uuid() . '.jpg',
            'name'  => fake()->words(2, true),
            'link'  => 'https://example.test',
        ];
    }
}
