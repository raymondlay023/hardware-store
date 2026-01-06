<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Seed the database by migrating existing category strings from products
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Get unique category names from existing products
            $categoryNames = Product::select('category')
                ->whereNotNull('category')
                ->distinct()
                ->pluck('category');

            $this->command->info("Found {$categoryNames->count()} unique categories");

            foreach ($categoryNames as $name) {
                if (empty(trim($name))) {
                    continue;
                }

                // Create category record
                $category = Category::create([
                    'name' => $name,
                    'slug' => Str::slug($name),
                ]);

                // Update all products with this category name to link to the new category
                Product::where('category', $name)->update([
                    'category_id' => $category->id,
                ]);

                $productCount = Product::where('category_id', $category->id)->count();

                $this->command->info("Created category: {$name} ({$productCount} products)");
            }

            $this->command->info("Successfully migrated all categories!");
        });
    }
}
