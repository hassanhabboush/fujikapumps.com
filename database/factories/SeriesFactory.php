<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\Series;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Series>
 */
class SeriesFactory extends Factory
{
    protected $model = Series::class;

    public function definition(): array
    {
        return [
            // string(40) in the schema, so keep the generated name short.
            'english_name' => fake()->unique()->lexify('Series ????'),
            'link'         => 'https://example.test',
            'photo'        => 'public/seriesuploads/' . fake()->uuid() . '.jpg',
            'text1'        => fake()->words(3, true),
            'text2'        => fake()->words(3, true),
            'text3'        => fake()->words(3, true),
            'family_id'    => Family::factory(),
            'enabled'      => 1,
        ];
    }
}
