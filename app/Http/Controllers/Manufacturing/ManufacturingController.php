<?php

namespace App\Http\Controllers\Manufacturing;

use App\Http\Controllers\Controller;
use App\Modules\Manufacturing\Models\MfgBom;
use App\Modules\Manufacturing\Models\MfgBomItem;
use App\Modules\Manufacturing\Models\ProductionService;
use App\Modules\Manufacturing\Models\MfgWorkCenter;
use Illuminate\Http\Request;

class ManufacturingController extends Controller
{
    public function index()
    {
        return view('manufacturing.index', [
            'boms' => MfgBom::all(),
            'workCenters' => MfgWorkCenter::all(),
        ]);
    }

    public function showBom($id)
    {
        $bom = MfgBom::with('items')->findOrFail($id);
        return view('manufacturing.bom-details', [
            'bom' => $bom,
            'items' => $bom->items
        ]);
    }

    public function productionOrders()
    {
        // In a real app, we'd have a ProductionOrder model.
        // For now, we simulate the list based on current activity.
        return view('manufacturing.orders', [
            'orders' => []
        ]);
    }
}
