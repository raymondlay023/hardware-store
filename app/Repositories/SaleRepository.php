<?php

namespace App\Repositories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;

class SaleRepository extends BaseRepository
{
    public function __construct(Sale $model)
    {
        parent::__construct($model);
    }

    /**
     * Get sales by date range
     */
    public function getByDateRange(string $from, string $to): Collection
    {
        return $this->model
            ->whereBetween('date', [$from, $to])
            ->with(['saleItems.product', 'customer'])
            ->latest('date')
            ->get();
    }

    /**
     * Get sales by customer
     */
    public function getByCustomer(int $customerId, int $limit = 10): Collection
    {
        return $this->model
            ->where('customer_id', $customerId)
            ->with('saleItems.product')
            ->latest('date')
            ->limit($limit)
            ->get();
    }

    /**
     * Get today's sales
     */
    public function getTodaysSales(): Collection
    {
        return $this->model
            ->whereDate('date', today())
            ->with(['saleItems.product', 'customer'])
            ->latest('created_at')
            ->get();
    }

    /**
     * Calculate revenue for date range
     */
    public function calculateRevenue(string $from, string $to): float
    {
        return $this->model
            ->whereBetween('date', [$from, $to])
            ->sum('total_amount');
    }

    /**
     * Get sales with full details
     */
    public function findWithDetails(int $id): ?Sale
    {
        return $this->model
            ->with(['saleItems.product', 'customer', 'user'])
            ->find($id);
    }
}
