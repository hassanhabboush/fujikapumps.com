<?php

namespace Database\Factories;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Slider>
 */
class SliderFactory extends Factory
{
    protected $model = Slider::class;

    public function definition(): array
    {
        return [
            'image'      => 'public/slideruploads/' . fake()->uuid() . '.jpg',
            'text1'      => fake()->words(3, true),
            'text2'      => fake()->words(3, true),
            'text3'      => fake()->words(3, true),
            'buttontext' => 'Read more',
            'buttonlink' => 'https://example.test',
        ];
    }
}
