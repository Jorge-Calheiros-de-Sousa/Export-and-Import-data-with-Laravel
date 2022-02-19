<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryContract
{
    public function paginateWithSearch(int $perPage, string $field = "", string $name = ""): LengthAwarePaginator;
}
