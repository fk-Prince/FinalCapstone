<?php

namespace App\Service;

use App\Repository\BedRepository;
use App\Http\Resources\BedResource;
use App\Models\User;

class BedService
{
    private BedRepository $bedRepository;

    public function __construct(BedRepository $bedRepository) 
    {
        $this->bedRepository = $bedRepository;
    }

    public function createBed(User $actor, array $payload)
    {
        if (! $actor->hasRole('superadmin')) {
            $payload['company_id'] = $actor->company_id;
        }

        $model = $this->bedRepository->create($payload);
        return new BedResource($model);
    }

    public function listBed(User $actor, int $perPage = 15)
    {
        $companyId = $actor->hasRole('superadmin') ? null : $actor->company_id;

        $collection = $this->bedRepository->paginate($perPage, $companyId);
        return BedResource::collection($collection);
    }

    /**
     * Helper to ensure the actor owns the record
     */
    private function findScoped(User $actor, string $uuid)
    {
        $model = $this->bedRepository->findByUuid($uuid);
        
        if (! $model) {
            abort(404, 'Resource not found');
        }

        if (! $actor->hasRole('superadmin')) {
            if ($model->company_id !== $actor->company_id) {
                abort(403, 'Unauthorized access to this resource.');
            }
        }
        return $model;
    }

    public function getBed(User $actor, string $uuid)
    {
        $model = $this->findScoped($actor, $uuid);
        return new BedResource($model);
    }

    public function updateBed(User $actor, string $uuid, array $payload)
    {
        $this->findScoped($actor, $uuid);
        
        unset($payload['company_id']); 

        $model = $this->bedRepository->update($uuid, $payload);
        return new BedResource($model);
    }

    public function deleteBed(User $actor, string $uuid)
    {
        $this->findScoped($actor, $uuid);
        $this->bedRepository->delete($uuid);
        return true;
    }

    public function restoreBed(User $actor, string $uuid)
    {
        $model = $this->bedRepository->restore($uuid);

        if (! $actor->hasRole('superadmin') && $model->company_id !== $actor->company_id) {
            $model->delete(); 
            abort(403, 'Unauthorized');
        }
        
        return new BedResource($model);
    }
}