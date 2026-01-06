<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    /**
     * Find customer by phone
     */
    public function findByPhone(string $phone): ?Customer
    {
        return $this->model->where('phone', $phone)->first();
    }

    /**
     * Find customer by email
     */
    public function findByEmail(string $email): ?Customer
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Get top customers by total purchases
     */
    public function getTopCustomers(int $limit = 10): Collection
    {
        return $this->model
            ->orderBy('total_purchases', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get customers by type
     */
    public function getByType(string $type): Collection
    {
        return $this->model->where('type', $type)->get();
    }

    /**
     * Search customers
     */
    public function search(string $query): Collection
    {
        return $this->model
            ->where('name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->get();
    }
}
