<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * Search products by name, brand, category, or aliases
     */
    public function searchByNameOrAlias(string $query, int $limit = 10): Collection
    {
        return $this->model
            ->where('name', 'like', "%{$query}%")
            ->orWhere('brand', 'like', "%{$query}%")
            ->orWhere('category', 'like', "%{$query}%")
            ->orWhereHas('aliases', function ($q) use ($query) {
                $q->where('alias', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get();
    }

    /**
     * Get low stock products
     */
    public function getLowStock(?int $threshold = null): Collection
    {
        $query = $this->model->query();

        if ($threshold) {
            $query->where('current_stock', '<', $threshold);
        } else {
            $query->whereRaw('current_stock < low_stock_threshold');
        }

        return $query->orderBy('current_stock', 'asc')->get();
    }

    /**
     * Get critical stock products
     */
    public function getCriticalStock(): Collection
    {
        return $this->model
            ->whereRaw('current_stock < critical_stock_threshold')
            ->orderBy('current_stock', 'asc')
            ->get();
    }

    /**
     * Find product with all relationships loaded
     */
    public function findWithRelations(int $id): ?Product
    {
        return $this->model
            ->with(['supplier', 'category', 'aliases', 'stockMovements'])
            ->find($id);
    }

    /**
     * Get products with stock above zero
     */
    public function getInStock(): Collection
    {
        return $this->model->where('current_stock', '>', 0)->get();
    }
}
