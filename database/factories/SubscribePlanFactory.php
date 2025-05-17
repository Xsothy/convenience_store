<?php

namespace Database\Factories;

use App\Models\SubscribePlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubscribePlan>
 */
class SubscribePlanFactory extends Factory
{
    protected $model = SubscribePlan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $planName = $this->faker->randomElement(['Basic', 'Premium', 'Pro', 'Enterprise']).' '.$this->faker->randomElement(['Plan', 'Subscription', 'Package']);

        return [
            'name' => $planName,
            'price' => $this->faker->randomElement([9.99, 19.99, 29.99, 49.99, 99.99]),
            'is_active' => true,
            'currency' => 'USD',
            'description' => $this->faker->paragraph(2),
            'features_list' => [
                "Feature 1 for $planName",
                "Feature 2 for $planName",
                "Feature 3 for $planName",
                $this->faker->sentence(),
            ],
        ];
    }

    /**
     * Indicate that the plan is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
