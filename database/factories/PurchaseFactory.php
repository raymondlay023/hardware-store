<?php

namespace Database\Factories;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalAmount = fake()->randomFloat(2, 100000, 10000000);

        return [
            'supplier_id' => Supplier::factory(),
            'date' => fake()->dateTimeBetween('-3 months', 'now'),
            'total_amount' => round($totalAmount, 2),
            'status' => fake()->randomElement(['pending', 'received']),
            // 'notes' => fake()->optional(0.3)->sentence(), // Removed: column doesn't exist
            // 'created_by' => User::factory(), // Removed: column doesn't exist  
        ];
    }

    /**
     * Indicate that the purchase is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the purchase has been received.
     */
    public function received(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'received',
        ]);
    }

    /**
     * Indicate a recent purchase (within last week).
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * Indicate a large purchase order.
     */
    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'total_amount' => fake()->randomFloat(2, 5000000, 20000000),
        ]);
    }
}
