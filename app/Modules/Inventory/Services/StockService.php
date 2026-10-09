<?php

namespace App\Modules\Inventory\Services;

use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Inventory\Models\InventoryStockLedger;
use App\Modules\Inventory\Models\InventoryLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class StockService
{
    /**
     * Record a stock movement.
     * This is the single point of entry for all stock changes.
     */
    public function moveStock(
        int $itemId,
        int $locationId,
        float $quantity,
        string $type,
        ?string $refType = null,
        ?int $refId = null,
        ?string $notes = null
    ): InventoryStockLedger {
        return DB::transaction(function () use ($itemId, $locationId, $quantity, $type, $refType, $refId, $notes) {

            // 1. CONCURRENCY CONTROL: Pessimistic Lock
            // We lock the item row to prevent other threads from calculating stock
            // while we are in the middle of a movement.
            InventoryItem::where('id', $itemId)->lockForUpdate()->first();

            // 2. Validation: Prevent negative stock for 'ISSUE' types
            if ($quantity < 0) {
                $currentStock = $this->getCurrentStock($itemId, $locationId);
                if (($currentStock + $quantity) < 0) {
                    throw new Exception("Insufficient stock at location. Available: {$currentStock}, Requested: " . abs($quantity));
                }
            }

            // 3. Record the immutable ledger entry
            $item = InventoryItem::findOrFail($itemId);

            return InventoryStockLedger::create([
                'organization_id' => Auth::user()?->organization_id ?? $item->organization_id,
                'inventory_item_id' => $itemId,
                'inventory_location_id' => $locationId,
                'quantity' => $quantity,
                'transaction_type' => $type,
                'reference_type' => $refType,
                'reference_id' => $refId,
                'transaction_date' => now(),
                'user_id' => Auth::id() ?? $item->organization_id,
                'notes' => $notes,
            ]);
        });
    }

    /**
     * Calculate current stock for an item at a specific location.
     */
    public function getCurrentStock(int $itemId, int $locationId): float
    {
        return (float) InventoryStockLedger::where('inventory_item_id', $itemId)
            ->where('inventory_location_id', $locationId)
            ->sum('quantity');
    }

    /**
     * Transfer stock between two locations.
     */
    public function transferStock(int $itemId, int $fromLocId, int $toLocId, float $qty, ?string $notes = null): void
    {
        DB::transaction(function () use ($itemId, $fromLocId, $toLocId, $qty, $notes) {
            // Out from source
            $this->moveStock($itemId, $fromLocId, -$qty, 'TRANSFER', 'InternalTransfer', null, "Transfer to Loc {$toLocId}: {$notes}");
            // In to destination
            $this->moveStock($itemId, $toLocId, $qty, 'TRANSFER', 'InternalTransfer', null, "Transfer from Loc {$fromLocId}: {$notes}");
        });
    }
}
