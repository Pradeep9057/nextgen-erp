<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Core\Traits\Filterable;

class Invoice extends Model
{
    use SoftDeletes, Filterable;

    protected $table = 'invoices';

    protected $fillable = [
        'organization_id', 'crm_account_id', 'sales_order_id', 'invoice_number',
        'invoice_date', 'due_date', 'total_amount', 'tax_amount', 'status', 'notes'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
