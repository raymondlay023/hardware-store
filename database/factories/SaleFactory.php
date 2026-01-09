<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalAmount = fake()->randomFloat(2, 50000, 5000000);
        $discountType = fake()->randomElement(['percentage', 'fixed', 'none']);
        $discountValue = 0;

        if ($discountType === 'percentage') {
            $discountValue = fake()->randomFloat(2, 5, 20); // 5-20%
        } elseif ($discountType === 'fixed') {
            $discountValue = fake()->randomFloat(2, 10000, 100000);
        }

        return [
            'customer_id' => Customer::factory(),
            'customer_name' => fake()->name(),
            'date' => fake()->dateTimeBetween('-6 months', 'now'),
            'total_amount' => round($totalAmount, 2),
            'discount_type' => $discountType,
            'discount_value' => round($discountValue, 2),
            'payment_method' => fake()->randomElement(['cash', 'transfer', 'card', 'check']),
            'notes' => fake()->optional(0.3)->sentence(),
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the sale has no discount.
     */
    public function noDiscount(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_type' => 'none',
            'discount_value' => 0,
        ]);
    }

    /**
     * Indicate that the sale has percentage discount.
     */
    public function withPercentageDiscount(float $percentage = 10): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_type' => 'percentage',
            'discount_value' => $percentage,
        ]);
    }

    /**
     * Indicate payment method is cash.
     */
    public function cash(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'cash',
        ]);
    }

    /**
     * Indicate payment method is transfer.
     */
    public function transfer(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'transfer',
        ]);
    }
}

