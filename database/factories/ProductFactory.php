<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cost = fake()->randomFloat(2, 5000, 500000);
        $markup = fake()->randomFloat(2, 1.1, 1.5); // 10-50% markup
        $price = $cost * $markup;

        return [
            'name' => fake()->words(3, true),
            'brand' => fake()->randomElement(['Gresik', 'Tiga Roda', 'Rucika', 'Supreme', 'KS', 'Avitex', 'Propan', 'BJA', 'Broco', 'Lokal']),
            'unit' => fake()->randomElement(['pcs', 'box', 'sak', 'meter', 'batang', 'lembar', 'kaleng', 'pail', 'm3']),
            'cost' => round($cost, 2),
            'price' => round($price, 2),
            'current_stock' => fake()->numberBetween(0, 500),
            'low_stock_threshold' => fake()->numberBetween(10, 50),
            'category_id' => Category::factory(),
            'supplier_id' => Supplier::factory(),
        ];
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_stock' => 0,
        ]);
    }

    /**
     * Indicate that the product has low stock.
     */
    public function lowStock(): static
    {
        return $this->state(function (array $attributes) {
            $threshold = $attributes['low_stock_threshold'] ?? 20;
            return [
                'current_stock' => fake()->numberBetween(1, $threshold - 1),
            ];
        });
    }

    /**
     * Indicate that the product is expensive.
     */
    public function expensive(): static
    {
        return $this->state(fn (array $attributes) => [
            'cost' => fake()->randomFloat(2, 500000, 2000000),
            'price' => fake()->randomFloat(2, 600000, 2500000),
        ]);
    }

    /**
     * Indicate that the product is cheap/budget.
     */
    public function budget(): static
    {
        return $this->state(fn (array $attributes) => [
            'cost' => fake()->randomFloat(2, 1000, 10000),
            'price' => fake()->randomFloat(2, 1500, 15000),
        ]);
    }

    /**
     * Indicate that the product has good stock.
     */
    public function inStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_stock' => fake()->numberBetween(100, 1000),
        ]);
    }
}
