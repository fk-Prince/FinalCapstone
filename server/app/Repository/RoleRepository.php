<?php

namespace App\Repository;

use App\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleRepository
{

    public function findByUuid(string $column, string $value)
    {
        return Role::where($column,  $value)->first();
    }
}
