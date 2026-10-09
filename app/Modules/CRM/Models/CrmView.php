<?php

namespace App\Modules\CRM\Models;

use Illuminate\Database\Eloquent\Model;

class CrmView extends Model
{
    protected $fillable = [
        'organization_id',
        'user_id',
        'entity_type', // e.g., 'Lead', 'Account', 'Opportunity'
        'name',
        'filters', // JSON: { "status": "Qualified", "industry": "Enterprise" }
        'sort_by',  // Column name
        'sort_order', // 'asc' or 'desc'
        'columns',   // JSON: array of columns to display
        'is_default',
    ];

    protected $casts = [
        'filters' => 'array',
        'columns' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
