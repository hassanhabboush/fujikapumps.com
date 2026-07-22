<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductParameter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductParameter>
 */
class ProductParameterFactory extends Factory
{
    protected $model = ProductParameter::class;

    public function definition(): array
    {
        return [
            'product_id'   => Product::factory(),
            'Model'        => fake()->bothify('M-###'),
            'SerialNumber' => fake()->bothify('SN-####'),
            'PowerKw'      => '1.5',
            'PowerHp'      => '2',
            'Hertz'        => '50',
            'RPM'          => '1450',
        ];
    }
}
