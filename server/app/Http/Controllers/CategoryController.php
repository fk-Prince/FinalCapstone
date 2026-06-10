<?php

namespace App\Http\Controllers;

use App\Service\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        return $this->categoryService->listCategory(
            $request->user(), 
            $request->input('per_page', 15)
        );
    }

    public function store(Request $request)
    {
        return $this->categoryService->createCategory($request->user(), $request->all());
    }

    public function show(Request $request, string $uuid)
    {
        return $this->categoryService->getCategory($request->user(), $uuid);
    }

    public function update(Request $request, string $uuid)
    {
        return $this->categoryService->updateCategory($request->user(), $uuid, $request->all());
    }

    public function destroy(Request $request, string $uuid)
    {
        $this->categoryService->deleteCategory($request->user(), $uuid);
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
    
    public function restore(Request $request, string $uuid)
    {
        return $this->categoryService->restoreCategory($request->user(), $uuid);
    }
}