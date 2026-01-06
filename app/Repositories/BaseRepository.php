<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;
    protected array $with = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function all(): Collection
    {
        $query = $this->model->query();

        if (!empty($this->with)) {
            $query->with($this->with);
        }

        return $query->get();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $model = $this->findOrFail($id);
        $model->update($data);
        return $model->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->findOrFail($id)->delete();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($this->with)) {
            $query->with($this->with);
        }

        return $query->paginate($perPage);
    }

    public function where(string $column, $value): Collection
    {
        return $this->model->where($column, $value)->get();
    }

    public function with(array $relations): self
    {
        $this->with = $relations;
        return $this;
    }

    /**
     * Reset query modifiers
     */
    protected function resetQuery(): void
    {
        $this->with = [];
    }
}
