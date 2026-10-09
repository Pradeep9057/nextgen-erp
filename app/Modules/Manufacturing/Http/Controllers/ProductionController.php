<?php

namespace App\Modules\Manufacturing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Manufacturing\Services\ProductionService;
use Illuminate\Http\Request;
use App\Core\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ProductionController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ProductionService $productionService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'mfg_bom_id' => 'required|exists:mfg_boms,id',
            'organization_id' => 'required|exists:organizations,id',
            'quantity' => 'required|numeric|gt:0',
            'start_date' => 'nullable|date',
        ]);

        try {
            $order = $this->productionService->createProductionOrder($request->all());
            return $this->successResponse($order, 'Production order created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function consume(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'location_id' => 'required|exists:inventory_locations,id',
            'consumptions' => 'required|array|min:1',
            'consumptions.*.item_id' => 'required|exists:inventory_items,id',
            'consumptions.*.quantity' => 'required|numeric|gt:0',
        ]);

        try {
            $this->productionService->consumeMaterials($id, $request->location_id, $request->consumptions);
            return $this->successResponse(null, 'Materials consumed successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function complete(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'location_id' => 'required|exists:inventory_locations,id',
            'actual_quantity' => 'required|numeric|gt:0',
        ]);

        try {
            $this->productionService->completeProduction($id, $request->location_id, $request->actual_quantity);
            return $this->successResponse(null, 'Production completed and stock updated');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
