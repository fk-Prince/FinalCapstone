<?php

namespace App\Service;

use App\Repository\BranchRepository;
use App\Http\Resources\BranchResource;
use App\Models\User;

class BranchService
{
    private BranchRepository $branchRepository;

    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }
}
