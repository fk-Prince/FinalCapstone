<?php

namespace App\Http\Controllers;

use App\Service\RoomService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    private RoomService $roomService;

    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }

    public function index(Request $request)
    {
        return $this->roomService->listRoom(
            $request->user(), 
            $request->input('per_page', 15)
        );
    }

    public function store(Request $request)
    {
        return $this->roomService->createRoom($request->user(), $request->all());
    }

    public function show(Request $request, string $uuid)
    {
        return $this->roomService->getRoom($request->user(), $uuid);
    }

    public function update(Request $request, string $uuid)
    {
        return $this->roomService->updateRoom($request->user(), $uuid, $request->all());
    }

    public function destroy(Request $request, string $uuid)
    {
        $this->roomService->deleteRoom($request->user(), $uuid);
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
    
    public function restore(Request $request, string $uuid)
    {
        return $this->roomService->restoreRoom($request->user(), $uuid);
    }
}