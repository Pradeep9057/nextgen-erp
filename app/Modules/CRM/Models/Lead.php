<?php

namespace App\Modules\CRM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $table = 'crm_leads';
    protected $fillable = [
        'organization_id', 'first_name', 'last_name', 'email', 'phone', 'company_name', 'source', 'status', 'notes'
    ];
}
