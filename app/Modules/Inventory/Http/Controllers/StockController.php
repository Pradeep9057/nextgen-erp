<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Services\StockService;
use Illuminate\Http\Request;
use App\Core\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class StockController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected StockService $stockService
    ) {}

    public function adjust(Request $request): JsonResponse
    {
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'location_id' => 'required|exists:inventory_locations,id',
            'quantity' => 'required|numeric',
            'type' => 'required|string|in:ADJUSTMENT,RECEIPT,ISSUE',
            'notes' => 'nullable|string'
        ]);

        try {
            $ledger = $this->stockService->moveStock(
                $request->item_id,
                $request->location_id,
                $request->quantity,
                $request->type,
                'ManualAdjustment',
                null,
                $request->notes
            );
            return $this->successResponse($ledger, 'Stock adjusted successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function balance(int $itemId, int $locationId): JsonResponse
    {
        $balance = $this->stockService->getCurrentStock($itemId, $locationId);
        return $this->successResponse(['item_id' => $itemId, 'location_id' => $locationId, 'balance' => $balance]);
    }

    public function transfer(Request $request): JsonResponse
    {
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'from_location_id' => 'required|exists:inventory_locations,id',
            'to_location_id' => 'required|exists:inventory_locations,id',
            'quantity' => 'required|numeric|gt:0',
            'notes' => 'nullable|string'
        ]);

        try {
            $this->stockService->transferStock(
                $request->item_id,
                $request->from_location_id,
                $request->to_location_id,
                $request->quantity,
                $request->notes
            );
            return $this->successResponse(null, 'Stock transferred successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
