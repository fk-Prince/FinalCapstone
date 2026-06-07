<?php

namespace App\Repository;

use App\Models\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository
{

    public function create(array $payload)
    {
        return User::create($payload);
    }

    public function findByField(string $column, string $value)
    {
        return User::where($column, $value)->first();
    }

    public function updateOrCreate(object $payload)
    {
        return User::updateOrCreate(
            ['email' => $payload->getEmail()],
            [
                'first_name'      => explode(' ', $payload->getName())[0],
                'last_name'       => explode(' ', $payload->getName())[1] ?? '',
                'uuid'            => $payload->getId(),
                'provider'        => 'google',
                'password'        => null,
                'profile_picture' => $payload->getAvatar(),
            ]
        );
    }
}
