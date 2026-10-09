<?php

namespace App\Modules\CRM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opportunity extends Model
{
    use SoftDeletes, Filterable;

    protected $fillable = [
        'organization_id', 'crm_account_id', 'crm_contact_id', 'title',
        'estimated_value', 'stage', 'expected_close_date', 'probability', 'description'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(OpportunityHistory::class);
    }
}
