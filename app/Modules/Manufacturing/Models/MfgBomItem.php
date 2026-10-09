<?php

namespace App\Modules\Manufacturing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MfgBomItem extends Model
{
    protected $fillable = ['mfg_bom_id', 'component_item_id', 'quantity_required', 'uom_override', 'sort_order'];

    public function bom(): BelongsTo
    {
        return $this->belongsTo(MfgBom::class);
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Inventory\Models\InventoryItem::class, 'component_item_id');
    }
}
