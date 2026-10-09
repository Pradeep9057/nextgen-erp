<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id', 'inventory_category_id', 'inventory_uom_id', 'sku', 'name', 'description', 'min_stock_level', 'max_stock_level', 'is_trackable'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(InventoryCategory::class);
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(InventoryUom::class);
    }
}
