<?php

namespace App\Modules\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SalesOrderItem extends Model
{
    protected $fillable = ['orderable_id', 'orderable_type', 'product_sku', 'description', 'quantity', 'unit_price', 'discount', 'tax_rate', 'total_price'];

    public function orderable(): MorphTo
    {
        return $this->morphTo();
    }
}
