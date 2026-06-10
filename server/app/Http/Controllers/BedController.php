<?php

namespace App\Http\Controllers;

use App\Service\BedService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BedController extends Controller
{
    private BedService $bedService;

    public function __construct(BedService $bedService)
    {
        $this->bedService = $bedService;
    }

    public function index(Request $request)
    {
        return $this->bedService->listBed(
            $request->user(), 
            $request->input('per_page', 15)
        );
    }

    public function store(Request $request)
    {
        return $this->bedService->createBed($request->user(), $request->all());
    }

    public function show(Request $request, string $uuid)
    {
        return $this->bedService->getBed($request->user(), $uuid);
    }

    public function update(Request $request, string $uuid)
    {
        return $this->bedService->updateBed($request->user(), $uuid, $request->all());
    }

    public function destroy(Request $request, string $uuid)
    {
        $this->bedService->deleteBed($request->user(), $uuid);
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
    
    public function restore(Request $request, string $uuid)
    {
        return $this->bedService->restoreBed($request->user(), $uuid);
    }
}