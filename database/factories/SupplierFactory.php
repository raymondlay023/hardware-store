<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companyTypes = ['PT', 'CV', 'UD', 'Toko'];
        $industries = [
            'Semen Indonesia',
            'Baja Mandiri',
            'Cat Jaya',
            'Besi Mega',
            'Pipa Raya',
            'Listrik Sejahtera',
            'Bangunan Makmur',
        ];

        return [
            'name' => fake()->randomElement($companyTypes) . ' ' . fake()->randomElement($industries),
            'contact' => fake()->numerify('08##########'),
            'email' => fake()->optional(0.7)->companyEmail(),
            'address' => fake()->optional(0.8)->address(),
            'payment_terms' => fake()->optional(0.5)->randomElement(['COD', 'Net 30', 'Net 45', 'Net 60']),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    /**
     * Indicate that the supplier has payment terms.
     */
    public function withPaymentTerms(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_terms' => fake()->randomElement(['COD', 'Net 30', 'Net 45', 'Net 60']),
        ]);
    }
}
