<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(), // Ensure it belongs to a product
            'for' => $this->faker->randomElement(['retail', 'wholesale', 'special', 'items']),
            'qty' => $this->faker->numberBetween(10, 100),
            'price' => $this->faker->randomFloat(2, 5, 500),
            'vat' => $this->faker->randomFloat(2, 0, 20),
            'discount' => $this->faker->randomFloat(2, 0, 50),
            'discount_to' => $this->faker->boolean(50) ? $this->faker->dateTimeBetween('now', '+1 month') : null,
        ];
    }
}
