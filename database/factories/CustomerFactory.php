<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['retail', 'wholesale', 'contractor'];
        $type = fake()->randomElement($types);

        return [
            'name' => $this->generateName($type),
            'type' => $type,
            'phone' => fake()->numerify('08##########'),
            'email' => fake()->optional(0.6)->safeEmail(),
            'address' => fake()->optional(0.7)->address(),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    /**
     * Generate appropriate name based on customer type.
     */
    private function generateName(string $type): string
    {
        return match($type) {
            'retail' => fake()->name(),
            'wholesale' => 'Toko ' . fake()->company(),
            'contractor' => fake()->randomElement(['CV', 'PT', 'UD']) . ' ' . fake()->company(),
            default => fake()->name(),
        };
    }

    /**
     * Indicate that the customer is retail type.
     */
    public function retail(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'retail',
            'name' => fake()->name(),
        ]);
    }

    /**
     * Indicate that the customer is wholesale type.
     */
    public function wholesale(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'wholesale',
            'name' => 'Toko ' . fake()->company(),
        ]);
    }

    /**
     * Indicate that the customer is contractor type.
     */
    public function contractor(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'contractor',
            'name' => fake()->randomElement(['CV', 'PT', 'UD']) . ' ' . fake()->company(),
        ]);
    }

    /**
     * Indicate that the customer has email.
     */
    public function withEmail(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => fake()->safeEmail(),
        ]);
    }

    /**
     * Indicate that the customer has no email.
     */
    public function withoutEmail(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => null,
        ]);
    }
}
