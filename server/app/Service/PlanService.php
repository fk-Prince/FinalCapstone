<?php

namespace App\Service;

use App\Repository\PlanRepository;


class PlanService
{
    private PlanRepository $planRepository;

    public function __construct(PlanRepository $planRepository)
    {
        $this->planRepository = $planRepository;
    }

    public function getPlans()
    {
        return $this->planRepository->getPlans();
    }
}
