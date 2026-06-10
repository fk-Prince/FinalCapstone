<?php

namespace App\Service;

use App\Repository\CategoryRepository;
use App\Http\Resources\CategoryResource;
use App\Models\User;

class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) 
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function createCategory(User $actor, array $payload)
    {
        if (! $actor->hasRole('superadmin')) {
            $payload['company_id'] = $actor->company_id;
        }

        $model = $this->categoryRepository->create($payload);
        return new CategoryResource($model);
    }

    public function listCategory(User $actor, int $perPage = 15)
    {
        $companyId = $actor->hasRole('superadmin') ? null : $actor->company_id;

        $collection = $this->categoryRepository->paginate($perPage, $companyId);
        return CategoryResource::collection($collection);
    }

    /**
     * Helper to ensure the actor owns the record
     */
    private function findScoped(User $actor, string $uuid)
    {
        $model = $this->categoryRepository->findByUuid($uuid);
        
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

    public function getCategory(User $actor, string $uuid)
    {
        $model = $this->findScoped($actor, $uuid);
        return new CategoryResource($model);
    }

    public function updateCategory(User $actor, string $uuid, array $payload)
    {
        $this->findScoped($actor, $uuid);
        
        unset($payload['company_id']); 

        $model = $this->categoryRepository->update($uuid, $payload);
        return new CategoryResource($model);
    }

    public function deleteCategory(User $actor, string $uuid)
    {
        $this->findScoped($actor, $uuid);
        $this->categoryRepository->delete($uuid);
        return true;
    }

    public function restoreCategory(User $actor, string $uuid)
    {
        $model = $this->categoryRepository->restore($uuid);

        if (! $actor->hasRole('superadmin') && $model->company_id !== $actor->company_id) {
            $model->delete(); 
            abort(403, 'Unauthorized');
        }
        
        return new CategoryResource($model);
    }
}