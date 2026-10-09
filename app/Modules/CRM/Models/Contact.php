<?php

namespace App\Modules\CRM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\Filterable;

class Contact extends Model
{
    use SoftDeletes, Filterable;

    protected $fillable = ['crm_account_id', 'first_name', 'last_name', 'email', 'phone', 'job_title'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
