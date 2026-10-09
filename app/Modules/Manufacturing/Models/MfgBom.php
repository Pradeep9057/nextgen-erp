<?php

namespace App\Modules\Manufacturing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MfgBom extends Model
{
    protected $fillable = ['organization_id', 'finished_item_id', 'bom_number', 'version', 'quantity_to_produce', 'is_active'];

    public function items(): HasMany
    {
        return $this->hasMany(MfgBomItem::class);
    }

    public function finishedItem(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Inventory\Models\InventoryItem::class, 'finished_item_id');
    }
}
