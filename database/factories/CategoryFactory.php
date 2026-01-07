<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Semen & Pasir',
            'Besi & Baja',
            'Cat & Finishing',
            'Pipa & Fitting',
            'Listrik & Kabel',
            'Alat Tukang',
            'Kayu & Triplek',
            'Keramik & Granit',
            'Sanitair',
            'Pintu & Jendela',
        ];

        $name = fake()->unique()->randomElement($categories);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
