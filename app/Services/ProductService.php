<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Exceptions\BusinessLogicException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ProductService
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    /**
     * Create a new product
     *
     * @param array $productData
     * @param array $aliases Optional array of alias strings
     * @return Product
     */
    public function createProduct(array $productData, array $aliases = []): Product
    {
        return DB::transaction(function () use ($productData, $aliases) {
            // Create product with initial stock of 0
            $product = Product::create(array_merge($productData, [
                'current_stock' => $productData['current_stock'] ?? 0,
            ]));

            // Add aliases if provided
            if (!empty($aliases)) {
                $this->updateAliases($product, $aliases);
            }

            return $product->load('aliases', 'supplier', 'category');
        });
    }

    /**
     * Update an existing product
     *
     * @param int $productId
     * @param array $productData
     * @param array $aliases
     * @return Product
     */
    public function updateProduct(int $productId, array $productData, array $aliases = []): Product
    {
        return DB::transaction(function () use ($productId, $productData, $aliases) {
            $product = Product::findOrFail($productId);
            $product->update($productData);

            // Update aliases
            $this->updateAliases($product, $aliases);

            return $product->load('aliases', 'supplier', 'category');
        });
    }

    /**
     * Update product aliases
     *
     * @param Product $product
     * @param array $aliases
     */
    protected function updateAliases(Product $product, array $aliases): void
    {
        // Remove existing aliases
        $product->aliases()->delete();

        // Add new aliases (filter out empty ones)
        $filteredAliases = array_filter($aliases, fn($alias) => !empty(trim($alias)));

        foreach ($filteredAliases as $alias) {
            ProductAlias::create([
                'product_id' => $product->id,
                'alias' => trim($alias),
            ]);
        }
    }

    /**
     * Search products by name, brand, category, or aliases
     *
     * @param string $query
     * @param int $limit
     * @return Collection
     */
    public function searchProducts(string $query, int $limit = 10): Collection
    {
        return $this->productRepository->searchByNameOrAlias($query, $limit);
    }

    /**
     * Get low stock products
     *
     * @param int|null $threshold
     * @return Collection
     */
    public function getLowStockProducts(?int $threshold = null): Collection
    {
        return $this->productRepository->getLowStock($threshold);
    }

    /**
     * Calculate suggested reorder quantity for a product
     *
     * @param int $productId
     * @return int
     */
    public function calculateReorderQuantity(int $productId): int
    {
        $product = Product::findOrFail($productId);

        // If reorder quantity is set, use it
        if ($product->reorder_quantity) {
            return $product->reorder_quantity;
        }

        // Otherwise, calculate based on sales velocity (simplified)
        // This could be enhanced with actual sales data analysis
        return max($product->low_stock_threshold * 2, 10);
    }
}
