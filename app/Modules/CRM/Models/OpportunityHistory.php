<?php

namespace App\Modules\CRM\Models;

use Illuminate\Database\Eloquent\Model;

class OpportunityHistory extends Model
{
    public $timestamps = false;

    protected $fillable = ['crm_opportunity_id', 'from_stage', 'to_stage', 'changed_at', 'notes', 'changed_by'];
}
