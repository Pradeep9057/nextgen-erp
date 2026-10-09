<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryStockLedger extends Model
{
    protected $table = 'inventory_stock_ledgers';
    protected $fillable = [
        'organization_id', 'inventory_item_id', 'inventory_location_id',
        'quantity', 'transaction_type', 'reference_type', 'reference_id',
        'transaction_date', 'user_id', 'notes'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(InventoryLocation::class, 'inventory_location_id');
    }
}
