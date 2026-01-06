<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    /**
     * Find a model by ID
     */
    public function find(int $id): ?Model;

    /**
     * Find a model by ID or fail
     */
    public function findOrFail(int $id): Model;

    /**
     * Get all models
     */
    public function all(): Collection;

    /**
     * Create a new model
     */
    public function create(array $data): Model;

    /**
     * Update a model
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete a model
     */
    public function delete(int $id): bool;

    /**
     * Get paginated results
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find models where condition matches
     */
    public function where(string $column, $value): Collection;

    /**
     * Load relationships
     */
    public function with(array $relations): self;
}
