<?php

namespace App\Service;

use App\Repository\ServiceRepository;
use App\Http\Resources\ServiceResource;
use App\Models\User;

class ServiceService
{
    private ServiceRepository $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository) 
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function createService(User $actor, array $payload)
    {
        if (! $actor->hasRole('superadmin')) {
            $payload['company_id'] = $actor->company_id;
        }

        $model = $this->serviceRepository->create($payload);
        return new ServiceResource($model);
    }

    public function listService(User $actor, int $perPage = 15)
    {
        $companyId = $actor->hasRole('superadmin') ? null : $actor->company_id;

        $collection = $this->serviceRepository->paginate($perPage, $companyId);
        return ServiceResource::collection($collection);
    }

    /**
     * Helper to ensure the actor owns the record
     */
    private function findScoped(User $actor, string $uuid)
    {
        $model = $this->serviceRepository->findByUuid($uuid);
        
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

    public function getService(User $actor, string $uuid)
    {
        $model = $this->findScoped($actor, $uuid);
        return new ServiceResource($model);
    }

    public function updateService(User $actor, string $uuid, array $payload)
    {
        $this->findScoped($actor, $uuid);
        
        unset($payload['company_id']); 

        $model = $this->serviceRepository->update($uuid, $payload);
        return new ServiceResource($model);
    }

    public function deleteService(User $actor, string $uuid)
    {
        $this->findScoped($actor, $uuid);
        $this->serviceRepository->delete($uuid);
        return true;
    }

    public function restoreService(User $actor, string $uuid)
    {
        $model = $this->serviceRepository->restore($uuid);

        if (! $actor->hasRole('superadmin') && $model->company_id !== $actor->company_id) {
            $model->delete(); 
            abort(403, 'Unauthorized');
        }
        
        return new ServiceResource($model);
    }
}