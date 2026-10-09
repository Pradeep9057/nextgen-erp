<?php

namespace App\Modules\Manufacturing\Services;

use App\Modules\Manufacturing\Models\MfgBom;
use App\Modules\Manufacturing\Models\MfgProductionOrder;
use App\Modules\Manufacturing\Models\MfgProductionConsumption;
use App\Modules\Inventory\Services\StockService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class ProductionService
{
    public function __construct(
        protected StockService $stockService
    ) {}

    /**
     * Create a new production order based on a BOM.
     */
    public function createProductionOrder(array $data): MfgProductionOrder
    {
        return DB::transaction(function () use ($data) {
            $bom = MfgBom::findOrFail($data['mfg_bom_id']);

            return MfgProductionOrder::create([
                'organization_id' => $data['organization_id'],
                'mfg_bom_id' => $bom->id,
                'finished_item_id' => $bom->finished_item_id,
                'production_number' => 'PROD-' . strtoupper(Str::random(8)),
                'quantity_planned' => $data['quantity'],
                'status' => 'Planned',
                'start_date' => $data['start_date'] ?? null,
            ]);
        });
    }

    /**
     * Consume materials for a production order.
     */
    public function consumeMaterials(int $orderId, int $locationId, array $consumptions): void
    {
        DB::transaction(function () use ($orderId, $locationId, $consumptions) {
            $order = MfgProductionOrder::findOrFail($orderId);

            if ($order->status === 'Completed') {
                throw new Exception("Cannot consume materials for a completed order.");
            }

            $order->update(['status' => 'In Progress']);

            foreach ($consumptions as $item) {
                // 1. Move stock OUT of inventory (using StockService for lock/validation)
                $this->stockService->moveStock(
                    $item['item_id'],
                    $locationId,
                    -$item['quantity'],
                    'MANUFACTURING_CONSUMPTION',
                    'ProductionOrder',
                    $order->id,
                    "Consumption for Order {$order->production_number}"
                );

                // 2. Record in manufacturing consumption table
                MfgProductionConsumption::create([
                    'mfg_production_order_id' => $order->id,
                    'inventory_item_id' => $item['item_id'],
                    'inventory_location_id' => $locationId,
                    'quantity_consumed' => $item['quantity'],
                    'consumed_at' => now(),
                    'user_id' => Auth::id() ?? 1,
                ]);
            }
        });
    }

    /**
     * Complete production and add finished goods to stock.
     */
    public function completeProduction(int $orderId, int $locationId, float $actualQuantity): void
    {
        DB::transaction(function () use ($orderId, $locationId, $actualQuantity) {
            $order = MfgProductionOrder::findOrFail($orderId);

            // 1. Update order status
            $order->update([
                'quantity_produced' => $actualQuantity,
                'status' => 'Completed',
                'end_date' => now(),
            ]);

            // 2. Add finished goods to stock
            $this->stockService->moveStock(
                $order->finished_item_id,
                $locationId,
                $actualQuantity,
                'MANUFACTURING_OUTPUT',
                'ProductionOrder',
                $order->id,
                "Finished goods from Order {$order->production_number}"
            );
        });
    }
}
