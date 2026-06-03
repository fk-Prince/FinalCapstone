<?php

namespace App\Service;

use App\Repository\AgencyRepository;
use App\Http\Resources\AgencyResource;
use App\Models\User;

class AgencyService
{
    private AgencyRepository $agencyRepository;

    public function __construct(AgencyRepository $agencyRepository)
    {
        $this->agencyRepository = $agencyRepository;
    }

    public function listAgency(array $payload)
    {
        $collection = $this->agencyRepository->paginate($payload['per_page'], $payload['owned']);
        return AgencyResource::collection($collection);
    }
}
