<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryUom extends Model
{
    protected $table = 'inventory_uoms';
    protected $fillable = ['name', 'abbreviation', 'conversion_factor'];
}
