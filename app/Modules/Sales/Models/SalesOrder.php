<?php

namespace App\Modules\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Core\Traits\Filterable;

class SalesOrder extends Model
{
    use SoftDeletes, Filterable;

    protected $fillable = ['organization_id', 'crm_account_id', 'sales_quotation_id', 'order_number', 'order_date', 'total_amount', 'tax_amount', 'status', 'shipping_address'];

    public function items(): MorphMany
    {
        return $this->morphMany(SalesOrderItem::class, 'orderable');
    }
}
