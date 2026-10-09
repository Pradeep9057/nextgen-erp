<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Inventory\Models\InventoryWarehouse;
use App\Modules\Inventory\Models\InventoryLocation;
use App\Modules\Inventory\Services\StockService;
use App\Modules\Organization\Models\Organization;

class InventoryIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_movements_and_balances()
    {
        $org = Organization::create(['name' => 'Test Corp']);
        $user = \App\User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'organization_id' => $org->id,
        ]);

        $warehouse = InventoryWarehouse::create(['organization_id' => $org->id, 'name' => 'Main WH', 'code' => 'WH1']);
        $loc1 = InventoryLocation::create(['inventory_warehouse_id' => $warehouse->id, 'name' => 'Bin A']);
        $loc2 = InventoryLocation::create(['inventory_warehouse_id' => $warehouse->id, 'name' => 'Bin B']);

        // Setup required dependencies
        $category = \App\Modules\Inventory\Models\InventoryCategory::create([
            'name' => 'General',
            'slug' => 'general'
        ]);
        $uom = \App\Modules\Inventory\Models\InventoryUom::create([
            'name' => 'Pieces',
            'abbreviation' => 'pcs'
        ]);

        $item = InventoryItem::create([
            'organization_id' => $org->id,
            'sku' => 'SKU-001',
            'name' => 'Test Item',
            'inventory_category_id' => $category->id,
            'inventory_uom_id' => $uom->id,
            'is_trackable' => true
        ]);

        $stockService = app(StockService::class);

        // Act as the created user to satisfy user_id foreign key
        $this->actingAs($user);

        // 1. Receipt (Add stock)
        $stockService->moveStock($item->id, $loc1->id, 100, 'RECEIPT');
        $this->assertEquals(100, $stockService->getCurrentStock($item->id, $loc1->id));

        // 2. Transfer (Move stock)
        $stockService->transferStock($item->id, $loc1->id, $loc2->id, 30);

        $this->assertEquals(70, $stockService->getCurrentStock($item->id, $loc1->id));
        $this->assertEquals(30, $stockService->getCurrentStock($item->id, $loc2->id));

        // 3. Prevent Overselling (Negative Stock)
        $this->expectException(\Exception::class);
        $stockService->moveStock($item->id, $loc1->id, -100, 'ISSUE');
    }
}
