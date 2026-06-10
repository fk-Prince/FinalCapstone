<?php

namespace App\Service;

use App\Repository\RoomRepository;
use App\Http\Resources\RoomResource;
use App\Models\User;

class RoomService
{
    private RoomRepository $roomRepository;

    public function __construct(RoomRepository $roomRepository) 
    {
        $this->roomRepository = $roomRepository;
    }

    public function createRoom(User $actor, array $payload)
    {
        if (! $actor->hasRole('superadmin')) {
            $payload['company_id'] = $actor->company_id;
        }

        $model = $this->roomRepository->create($payload);
        return new RoomResource($model);
    }

    public function listRoom(User $actor, int $perPage = 15)
    {
        $companyId = $actor->hasRole('superadmin') ? null : $actor->company_id;

        $collection = $this->roomRepository->paginate($perPage, $companyId);
        return RoomResource::collection($collection);
    }

    /**
     * Helper to ensure the actor owns the record
     */
    private function findScoped(User $actor, string $uuid)
    {
        $model = $this->roomRepository->findByUuid($uuid);
        
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

    public function getRoom(User $actor, string $uuid)
    {
        $model = $this->findScoped($actor, $uuid);
        return new RoomResource($model);
    }

    public function updateRoom(User $actor, string $uuid, array $payload)
    {
        $this->findScoped($actor, $uuid);
        
        unset($payload['company_id']); 

        $model = $this->roomRepository->update($uuid, $payload);
        return new RoomResource($model);
    }

    public function deleteRoom(User $actor, string $uuid)
    {
        $this->findScoped($actor, $uuid);
        $this->roomRepository->delete($uuid);
        return true;
    }

    public function restoreRoom(User $actor, string $uuid)
    {
        $model = $this->roomRepository->restore($uuid);

        if (! $actor->hasRole('superadmin') && $model->company_id !== $actor->company_id) {
            $model->delete(); 
            abort(403, 'Unauthorized');
        }
        
        return new RoomResource($model);
    }
}