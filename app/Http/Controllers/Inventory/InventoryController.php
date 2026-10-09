<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Inventory\Models\InventoryStockLedger;
use App\Modules\Inventory\Models\InventoryWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        return view('inventory.index', [
            'items' => InventoryItem::with('category', 'uom')->get(),
            'warehouses' => InventoryWarehouse::all(),
        ]);
    }

    public function ledger($id)
    {
        $item = InventoryItem::findOrFail($id);
        $movements = InventoryStockLedger::where('inventory_item_id', $id)
            ->orderBy('transaction_date', 'desc')
            ->get();

        return view('inventory.ledger', [
            'item' => $item,
            'movements' => $movements
        ]);
    }

    public function warehouse($id)
    {
        $warehouse = InventoryWarehouse::findOrFail($id);
        $stock = DB::table('inventory_stock_ledgers')
            ->join('inventory_items', 'inventory_stock_ledgers.inventory_item_id', '=', 'inventory_items.id')
            ->select('inventory_items.name', DB::raw('SUM(quantity) as total_qty'))
            ->where('inventory_location_id', $id)
            ->groupBy('inventory_items.id', 'inventory_items.name')
            ->get();

        return view('inventory.warehouse', [
            'warehouse' => $warehouse,
            'stock' => $stock
        ]);
    }
}
