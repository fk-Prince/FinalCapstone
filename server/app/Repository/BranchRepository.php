<?php

namespace App\Repository;

use App\Models\Branch;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BranchRepository
{

    public function create(array $payload)
    {
        return Branch::create($payload);
    }
}
