<?php

namespace App\Repository;

use App\Models\Agency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AgencyRepository
{

    public function createAgency(array $payload)
    {
        return Agency::create($payload);
    }


    public function findAgencyByField(string $column, string $value)
    {
        return Agency::where($column, $value)->first();
    }

    public function paginate(int $perPage, bool $owned)
    {
        $query = Agency::query();

        if ($owned) {
            $query->where('registered_by', Auth::id());
        }

        return $query
            ->with(['locations', 'registered_by'])
            ->latest()
            ->paginate($perPage);
    }
}
