<?php

namespace App\Http\Controllers;

use App\Service\RoomTransferService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoomTransferController extends Controller
{
    private RoomTransferService $roomTransferService;

    public function __construct(RoomTransferService $roomTransferService)
    {
        $this->roomTransferService = $roomTransferService;
    }

    public function index(Request $request)
    {
        return $this->roomTransferService->listRoomTransfer(
            $request->user(), 
            $request->input('per_page', 15)
        );
    }

    public function store(Request $request)
    {
        return $this->roomTransferService->createRoomTransfer($request->user(), $request->all());
    }

    public function show(Request $request, string $uuid)
    {
        return $this->roomTransferService->getRoomTransfer($request->user(), $uuid);
    }

    public function update(Request $request, string $uuid)
    {
        return $this->roomTransferService->updateRoomTransfer($request->user(), $uuid, $request->all());
    }

    public function destroy(Request $request, string $uuid)
    {
        $this->roomTransferService->deleteRoomTransfer($request->user(), $uuid);
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
    
    public function restore(Request $request, string $uuid)
    {
        return $this->roomTransferService->restoreRoomTransfer($request->user(), $uuid);
    }
}