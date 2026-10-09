<?php

namespace App\Modules\Sales\Services;

use App\Modules\Sales\Models\SalesQuotation;
use App\Modules\Sales\Models\SalesOrder;
use App\Modules\Sales\Models\SalesOrderItem;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Inventory\Services\StockService;
use App\Core\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalesService extends BaseService
{
    public function __construct(
        \App\Modules\Sales\Models\SalesQuotation $quotationModel,
        \App\Core\Services\CacheService $cacheService,
        protected StockService $stockService
    ) {
        parent::__construct($quotationModel, $cacheService);
    }

    /**
     * Create a quotation with its line items and calculate totals.
     */
    public function createQuotation(array $data, array $items): SalesQuotation
    {
        return DB::transaction(function () use ($data, $items) {
            $quotation = SalesQuotation::create([
                'organization_id' => $data['organization_id'],
                'crm_account_id' => $data['crm_account_id'],
                'quotation_number' => 'QT-' . strtoupper(Str::random(8)),
                'issue_date' => $data['issue_date'] ?? now(),
                'expiry_date' => $data['expiry_date'] ?? now()->addDays(30),
                'status' => 'Draft',
                'notes' => $data['notes'] ?? null,
            ]);

            $totalAmount = 0;
            $taxAmount = 0;

            foreach ($items as $itemData) {
                // Validate SKU exists
                $inventoryItem = InventoryItem::where('sku', $itemData['product_sku'])->firstOrFail();

                $lineTotal = ($itemData['quantity'] * $itemData['unit_price']) - ($itemData['discount'] ?? 0);
                $itemTax = $lineTotal * (($itemData['tax_rate'] ?? 0) / 100);
                $finalLineTotal = $lineTotal + $itemTax;

                $quotation->items()->create([
                    'product_sku' => $itemData['product_sku'],
                    'description' => $itemData['description'] ?? $inventoryItem->name,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'discount' => $itemData['discount'] ?? 0,
                    'tax_rate' => $itemData['tax_rate'] ?? 0,
                    'total_price' => $finalLineTotal,
                ]);

                $totalAmount += $lineTotal;
                $taxAmount += $itemTax;
            }

            $quotation->update([
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
            ]);

            return $quotation;
        });
    }

    /**
     * Convert an accepted quotation into a formal Sales Order.
     */
    public function convertToOrder(int $quotationId, array $orderData): SalesOrder
    {
        return DB::transaction(function () use ($quotationId, $orderData) {
            $quotation = SalesQuotation::findOrFail($quotationId);

            if ($quotation->status !== 'Accepted') {
                throw new \Exception("Only accepted quotations can be converted to orders.");
            }

            $order = SalesOrder::create([
                'organization_id' => $quotation->organization_id,
                'crm_account_id' => $quotation->crm_account_id,
                'sales_quotation_id' => $quotation->id,
                'order_number' => 'SO-' . strtoupper(Str::random(8)),
                'order_date' => now(),
                'total_amount' => $quotation->total_amount,
                'tax_amount' => $quotation->tax_amount,
                'status' => 'Pending',
                'shipping_address' => $orderData['shipping_address'] ?? null,
            ]);

            // Copy items and reserve stock
            foreach ($quotation->items as $item) {
                $order->items()->create([
                    'product_sku' => $item->product_sku,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'discount' => $item->discount,
                    'tax_rate' => $item->tax_rate,
                    'total_price' => $item->total_price,
                ]);

                // Reserve stock if the item is trackable
                $inventoryItem = InventoryItem::where('sku', $item->product_sku)->first();
                if ($inventoryItem && $inventoryItem->is_trackable) {
                    // For reservation, we typically use a specific transaction type or separate reserve table
                    // Here we use a negative movement to represent reservation/allocation
                    // In a real system, we'd use a 'RESERVED' status in the ledger
                    $this->stockService->moveStock(
                        $inventoryItem->id,
                        1, // Default warehouse/location for reservation
                        -$item->quantity,
                        'RESERVATION',
                        'SalesOrder',
                        $order->id,
                        "Reservation for Order {$order->order_number}"
                    );
                }
            }

            $quotation->update(['status' => 'Converted']);

            return $order;
        });
    }
}
