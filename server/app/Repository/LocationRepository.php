<?php

namespace App\Repository;

use App\Models\Location;

class LocationRepository
{

    public function create(array $payload)
    {
        return Location::create($payload);
    }
}
