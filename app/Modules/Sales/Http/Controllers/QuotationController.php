<?php

namespace App\Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Sales\Services\SalesService;
use Illuminate\Http\Request;
use App\Core\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class QuotationController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected SalesService $salesService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'crm_account_id' => 'required|exists:crm_accounts,id',
            'items' => 'required|array|min:1',
            'items.*.product_sku' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            $items = $request->input('items');
            $data = $request->except('items');
            $quotation = $this->salesService->createQuotation($data, $items);

            return $this->successResponse($quotation->load('items'), 'Quotation created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function convertToOrder(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'shipping_address' => 'required|string'
        ]);

        try {
            $order = $this->salesService->convertToOrder($id, $request->all());
            return $this->successResponse($order->load('items'), 'Sales Order created from quotation');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
