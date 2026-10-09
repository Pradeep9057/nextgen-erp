<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryWarehouse extends Model
{
    protected $table = 'inventory_warehouses';
    protected $fillable = ['organization_id', 'name', 'code', 'address', 'is_active'];

    public function locations(): HasMany
    {
        return $this->hasMany(InventoryLocation::class);
    }
}
