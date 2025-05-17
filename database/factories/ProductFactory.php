<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        $category = Category::query()->inRandomOrder()->first();
        $hasMultiPrice = $this->faker->boolean();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'is_trend' => false,
            'type' => $this->faker->randomElement(['product', 'digital', 'service']),
            'vat' => $this->faker->randomFloat(2, 0, 20),
            'discount' => $this->faker->randomFloat(2, 0, 50),
            'discount_to' => $this->faker->boolean(50) ? $this->faker->dateTimeBetween('now', '+1 month') : null,
            'is_activated' => $this->faker->boolean(),
            'is_shipped' => $this->faker->boolean(),
            'has_multi_price' => $hasMultiPrice,
            'is_in_stock' => $this->faker->boolean(),
            'has_unlimited_stock' => $this->faker->boolean(),
            'has_max_cart' => $this->faker->boolean(),
            'min_cart' => $this->faker->numberBetween(1, 5),
            'max_cart' => $this->faker->numberBetween(5, 20),
            'has_stock_alert' => $this->faker->boolean(),
            'min_stock_alert' => $this->faker->numberBetween(1, 10),
            'max_stock_alert' => $this->faker->numberBetween(10, 50),
            'category_id' => $category ? $category->id : null,
            'has_options' => $this->faker->boolean(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            if ($product->has_multi_price) {
                // Create multiple prices for the product
                //                Price::factory()->count(rand(2, 5))->create([
                //                    'product_id' => $product->id,
                //                ]);
                // $product->addMediaFromUrl($this->getRandomImage())->toMediaCollection('feature_image');

                // $product->addMediaFromUrl($this->getRandomImage())->toMediaCollection('gallery');
                // $product->addMediaFromUrl($this->getRandomImage())->toMediaCollection('gallery');
                // $product->addMediaFromUrl($this->getRandomImage())->toMediaCollection('gallery');
                // $product->addMediaFromUrl($this->getRandomImage())->toMediaCollection('gallery');
            }
        });
    }

    private function getRandomImage(): string
    {
        return $this->faker->randomElement(['https://images.unsplash.com/photo-1549490349-8643362247b5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&h=500&q=80',
            'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&h=500&q=80',
            'https://images.unsplash.com/photo-1550745165-9bc0b252726f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&h=500&q=80']);
    }
}
