<?php

namespace App\Modules\Manufacturing\Services;

use App\Modules\Manufacturing\Models\MfgProductionOrder;
use App\Modules\Manufacturing\Models\MfgBom;
use App\Modules\Manufacturing\Models\MfgBomItem;
use App\Modules\Manufacturing\Models\MfgProductionConsumption;
use Illuminate\Support\Facades\DB;

class VarianceService
{
    /**
     * Calculate the variance for a specific production order.
     */
    public function getOrderVariance(int $orderId): array
    {
        $order = MfgProductionOrder::with(['mfgBom.items'])->findOrFail($orderId);
        $bom = $order->mfgBom;
        $plannedQty = $order->quantity_produced > 0 ? $order->quantity_produced : $order->quantity_planned;

        $actualConsumptions = MfgProductionConsumption::where('mfg_production_order_id', $orderId)
            ->select('inventory_item_id', DB::raw('SUM(quantity_consumed) as total_consumed'))
            ->groupBy('inventory_item_id')
            ->get()
            ->pluck('total_consumed', 'inventory_item_id');

        $variances = [];
        $totalPlannedCost = 0;
        $totalActualCost = 0;

        foreach ($bom->items as $bomItem) {
            $planned = $bomItem->quantity_required * ($plannedQty / $bom->quantity_to_produce);
            $actual = $actualConsumptions->get($bomItem->component_item_id, 0);
            $variance = $actual - $planned;

            $variances[] = [
                'item_id' => $bomItem->component_item_id,
                'item_name' => $bomItem->component->name,
                'planned' => $planned,
                'actual' => $actual,
                'variance' => $variance,
                'variance_percentage' => $planned > 0 ? ($variance / $planned) * 100 : 0,
            ];
        }

        return [
            'order_number' => $order->production_number,
            'planned_quantity' => $order->quantity_planned,
            'actual_quantity' => $order->quantity_produced,
            'material_variances' => $variances,
            'status' => $this->calculateOverallStatus($variances),
        ];
    }

    protected function calculateOverallStatus(array $variances): string
    {
        foreach ($variances as $v) {
            if ($v['variance'] > 0) return 'Over-Consumed';
        }
        return 'On Track';
    }
}
