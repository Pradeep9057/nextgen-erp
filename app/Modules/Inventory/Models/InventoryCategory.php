<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCategory extends Model
{
    protected $table = 'inventory_categories';
    protected $fillable = ['name', 'slug', 'description'];
}
