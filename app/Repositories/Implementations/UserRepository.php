<?php

namespace App\Repositories\Implementations;

use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\Implementations\AbstractRepository;
use App\Models\User;

class UserRepository extends AbstractRepository implements UserRepositoryContract
{
    protected $model = User::class;

    public function export()
    {
        return $this->model->all();
    }

    public function findValue($colunm, $value)
    {
        return $this->model->where($colunm, $value)->first();
    }

    public function import(array $users)
    {
        return $this->model->insert($users);
    }
}
