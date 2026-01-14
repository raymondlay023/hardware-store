<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockMovement>
 */
class StockMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['sale', 'purchase', 'adjustment_in', 'adjustment_out', 'initial']);
        $quantity = $type === 'sale' || $type === 'adjustment_out' 
            ? -fake()->numberBetween(1, 50)
            : fake()->numberBetween(1, 100);

        return [
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'type' => $type,
            'reason' => fake()->optional(0.5)->sentence(),
            'referenceable_type' => null,
            'referenceable_id' => null,
            'user_id' => User::factory(),
        ];
    }

    /**
     * Indicate a sale movement (stock out).
     */
    public function sale(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'sale',
            'quantity' => -fake()->numberBetween(1, 50),
            'referenceable_type' => Sale::class,
            'referenceable_id' => Sale::factory(),
        ]);
    }

    /**
     * Indicate a purchase movement (stock in).
     */
    public function purchase(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'purchase',
            'quantity' => fake()->numberBetween(10, 200),
            'referenceable_type' => Purchase::class,
            'referenceable_id' => Purchase::factory(),
        ]);
    }

    /**
     * Indicate an adjustment in movement.
     */
    public function adjustmentIn(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'adjustment_in',
            'quantity' => fake()->numberBetween(1, 50),
            'reason' => fake()->randomElement(['Found', 'Correction', 'Other']),
        ]);
    }

    /**
     * Indicate an adjustment out movement.
     */
    public function adjustmentOut(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'adjustment_out',
            'quantity' => -fake()->numberBetween(1, 50),
            'reason' => fake()->randomElement(['Damage', 'Theft', 'Correction', 'Other']),
        ]);
    }

    /**
     * Indicate an initial stock movement.
     */
    public function initial(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'initial',
            'quantity' => fake()->numberBetween(50, 500),
            'reason' => 'Initial stock',
        ]);
    }
}
