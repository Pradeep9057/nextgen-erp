<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GlEntry extends Model
{
    protected $fillable = [
        'organization_id', 'gl_account_id', 'debit', 'credit',
        'reference_type', 'reference_id', 'entry_date', 'description'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(GlAccount::class, 'gl_account_id');
    }
}
