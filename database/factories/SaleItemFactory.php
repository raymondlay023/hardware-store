<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleItem>
 */
class SaleItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitPrice = fake()->randomFloat(2, 10000, 500000);
        $quantity = fake()->numberBetween(1, 50);

        return [
            'sale_id' => Sale::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'unit_price' => round($unitPrice, 2),
        ];
    }

    /**
     * Indicate a large quantity sale item.
     */
    public function largeQuantity(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => fake()->numberBetween(50, 200),
        ]);
    }

    /**
     * Indicate an expensive sale item.
     */
    public function expensive(): static
    {
        return $this->state(fn (array $attributes) => [
            'unit_price' => fake()->randomFloat(2, 500000, 2000000),
        ]);
    }

    /**
     * Indicate a budget sale item.
     */
    public function budget(): static
    {
        return $this->state(fn (array $attributes) => [
            'unit_price' => fake()->randomFloat(2, 5000, 50000),
        ]);
    }
}
