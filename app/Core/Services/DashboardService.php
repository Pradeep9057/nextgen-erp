<?php

namespace App\Core\Services;

use App\Modules\CRM\Models\Lead;
use App\Modules\Sales\Models\SalesOrder;
use App\Modules\Inventory\Models\InventoryItem;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getKpis(): array
    {
        return [
            'total_revenue' => $this->calculateRevenue(),
            'active_leads' => Lead::where('status', 'active')->count(),
            'low_stock_items' => $this->getLowStockCount(),
            'pending_orders' => SalesOrder::where('status', 'pending')->count(),
        ];
    }

    protected function getLowStockCount(): int
    {
        // In an immutable ledger system, current stock is the sum of all transactions
        return (int) DB::table('inventory_items')
            ->join('inventory_stock_ledgers', 'inventory_items.id', '=', 'inventory_stock_ledgers.inventory_item_id')
            ->select('inventory_items.id')
            ->groupBy('inventory_items.id')
            ->havingRaw('SUM(inventory_stock_ledgers.quantity) < 10')
            ->get()
            ->count();
    }

    protected function calculateRevenue(): float

    {
        // Simplified revenue calculation from Sales Orders
        return (float) DB::table('sales_orders')->sum('total_amount') ?? 0.0;
    }
}
