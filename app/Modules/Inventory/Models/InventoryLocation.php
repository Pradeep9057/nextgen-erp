<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLocation extends Model
{
    protected $table = 'inventory_locations';
    protected $fillable = ['inventory_warehouse_id', 'name', 'code'];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(InventoryWarehouse::class);
    }
}
