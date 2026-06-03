<?php

namespace App\Repository;

use App\Models\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository
{

    // public function create(array $payload)
    // {
    //     return Auth::create($payload);
    // }

    public function findByField(string $column, string $value)
    {
        return User::where($column, $value)->first();
    }
}
