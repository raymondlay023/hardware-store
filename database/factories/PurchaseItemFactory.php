<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseItem>
 */
class PurchaseItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitCost = fake()->randomFloat(2, 5000, 500000);
        $quantity = fake()->numberBetween(1, 100);

        return [
            'purchase_id' => Purchase::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'unit_cost' => round($unitCost, 2),
        ];
    }

    /**
     * Indicate a large quantity item.
     */
    public function largeQuantity(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => fake()->numberBetween(100, 1000),
        ]);
    }

    /**
     * Indicate an expensive item.
     */
    public function expensive(): static
    {
        return $this->state(fn (array $attributes) => [
            'unit_cost' => fake()->randomFloat(2, 500000, 2000000),
        ]);
    }
}
