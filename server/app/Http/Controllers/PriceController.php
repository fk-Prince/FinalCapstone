<?php

namespace App\Http\Controllers;

use App\Service\PriceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PriceController extends Controller
{
    private PriceService $priceService;

    public function __construct(PriceService $priceService)
    {
        $this->priceService = $priceService;
    }

    public function index(Request $request)
    {
        return $this->priceService->listPrice(
            $request->user(), 
            $request->input('per_page', 15)
        );
    }

    public function store(Request $request)
    {
        return $this->priceService->createPrice($request->user(), $request->all());
    }

    public function show(Request $request, string $uuid)
    {
        return $this->priceService->getPrice($request->user(), $uuid);
    }

    public function update(Request $request, string $uuid)
    {
        return $this->priceService->updatePrice($request->user(), $uuid, $request->all());
    }

    public function destroy(Request $request, string $uuid)
    {
        $this->priceService->deletePrice($request->user(), $uuid);
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
    
    public function restore(Request $request, string $uuid)
    {
        return $this->priceService->restorePrice($request->user(), $uuid);
    }
}