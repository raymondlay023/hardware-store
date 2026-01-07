<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = [
            ['name' => 'admin', 'description' => 'System Administrator'],
            ['name' => 'manager', 'description' => 'Store Manager'],
            ['name' => 'cashier', 'description' => 'Cashier'],
            ['name' => 'staff', 'description' => 'General Staff'],
        ];

        $role = fake()->randomElement($roles);

        return [
            'name' => $role['name'],
            'description' => $role['description'],
        ];
    }

    /**
     * Indicate that the role is admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'admin',
            'description' => 'System Administrator',
        ]);
    }

    /**
     * Indicate that the role is manager.
     */
    public function manager(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'manager',
            'description' => 'Store Manager',
        ]);
    }

    /**
     * Indicate that the role is cashier.
     */
    public function cashier(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'cashier',
            'description' => 'Cashier',
        ]);
    }
}
