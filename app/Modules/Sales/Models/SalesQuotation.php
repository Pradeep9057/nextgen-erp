<?php

namespace App\Modules\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Core\Traits\Filterable;

class SalesQuotation extends Model
{
    use SoftDeletes, Filterable;

    protected $fillable = ['organization_id', 'crm_account_id', 'quotation_number', 'issue_date', 'expiry_date', 'total_amount', 'tax_amount', 'status', 'notes'];

    public function items(): HasMany
    {
        return $this->morphMany(SalesOrderItem::class, 'orderable');
    }
}
