<?php

namespace App\Repositories\Implementations;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class AbstractRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->resolveModel();
    }

    public function paginateWithSearch(int $perPage, string $field = "", string $name = ""): LengthAwarePaginator
    {
        $mainQuery = $this->model->when($name, function ($query) use ($name, $field) {
            $query->where($field, "like", "%$name%");
        });
        return $mainQuery->paginate($perPage);
    }
    public function getTable()
    {
        return $this->model->getTable();
    }

    public function resolveModel()
    {
        return app($this->model);
    }
}
