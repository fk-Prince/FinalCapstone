<?php

namespace App\Service;

use App\Repository\PriceRepository;
use App\Http\Resources\PriceResource;
use App\Models\User;

class PriceService
{
    private PriceRepository $priceRepository;

    public function __construct(PriceRepository $priceRepository) 
    {
        $this->priceRepository = $priceRepository;
    }

    public function createPrice(User $actor, array $payload)
    {
        if (! $actor->hasRole('superadmin')) {
            $payload['company_id'] = $actor->company_id;
        }

        $model = $this->priceRepository->create($payload);
        return new PriceResource($model);
    }

    public function listPrice(User $actor, int $perPage = 15)
    {
        $companyId = $actor->hasRole('superadmin') ? null : $actor->company_id;

        $collection = $this->priceRepository->paginate($perPage, $companyId);
        return PriceResource::collection($collection);
    }

    /**
     * Helper to ensure the actor owns the record
     */
    private function findScoped(User $actor, string $uuid)
    {
        $model = $this->priceRepository->findByUuid($uuid);
        
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

    public function getPrice(User $actor, string $uuid)
    {
        $model = $this->findScoped($actor, $uuid);
        return new PriceResource($model);
    }

    public function updatePrice(User $actor, string $uuid, array $payload)
    {
        $this->findScoped($actor, $uuid);
        
        unset($payload['company_id']); 

        $model = $this->priceRepository->update($uuid, $payload);
        return new PriceResource($model);
    }

    public function deletePrice(User $actor, string $uuid)
    {
        $this->findScoped($actor, $uuid);
        $this->priceRepository->delete($uuid);
        return true;
    }

    public function restorePrice(User $actor, string $uuid)
    {
        $model = $this->priceRepository->restore($uuid);

        if (! $actor->hasRole('superadmin') && $model->company_id !== $actor->company_id) {
            $model->delete(); 
            abort(403, 'Unauthorized');
        }
        
        return new PriceResource($model);
    }
}