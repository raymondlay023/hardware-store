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
        $subtotal = fake()->randomFloat(2, 50000, 5000000);
        $discountType = fake()->randomElement(['percentage', 'fixed', null]);
        $discount = 0;

        if ($discountType === 'percentage') {
            $discountValue = fake()->randomFloat(2, 5, 20); // 5-20%
            $discount = ($subtotal * $discountValue) / 100;
        } elseif ($discountType === 'fixed') {
            $discount = fake()->randomFloat(2, 10000, 100000);
        }

        $tax = 0; // PPN if needed
        $total = $subtotal - $discount + $tax;

        return [
            'customer_id' => Customer::factory(),
            'user_id' => User::factory(),
            'subtotal' => round($subtotal, 2),
            'discount_type' => $discountType,
            'discount_value' => $discountType ? round($discountType === 'percentage' ? ($discount / $subtotal) * 100 : $discount, 2) : 0,
            'discount' => round($discount, 2),
            'tax' => round($tax, 2),
            'total' => round($total, 2),
            'payment_method' => fake()->randomElement(['cash', 'transfer', 'credit']),
            'notes' => fake()->optional(0.3)->sentence(),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the sale has no discount.
     */
    public function noDiscount(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_type' => null,
            'discount_value' => 0,
            'discount' => 0,
            'total' => $attributes['subtotal'] + $attributes['tax'],
        ]);
    }

    /**
     * Indicate that the sale has percentage discount.
     */
    public function withPercentageDiscount(float $percentage = 10): static
    {
        return $this->state(function (array $attributes) use ($percentage) {
            $discount = ($attributes['subtotal'] * $percentage) / 100;
            return [
                'discount_type' => 'percentage',
                'discount_value' => $percentage,
                'discount' => round($discount, 2),
                'total' => round($attributes['subtotal'] - $discount + $attributes['tax'], 2),
            ];
        });
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
