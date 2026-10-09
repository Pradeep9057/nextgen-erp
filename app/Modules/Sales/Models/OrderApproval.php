<?php

namespace App\Modules\Sales\Models;

use Illuminate\Database\Eloquent\Model;

class OrderApproval extends Model
{
    protected $fillable = ['sales_order_id', 'user_id', 'status', 'comments', 'approved_at'];

    public const STATUS_PENDING = 'Pending';
    public const STATUS_APPROVED = 'Approved';
    public const STATUS_REJECTED = 'Rejected';

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function order()
    {
        return $this->belongsTo(SalesOrder::class);
    }
}
