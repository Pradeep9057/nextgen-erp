<?php

namespace App\Modules\Manufacturing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MfgRoutingStep extends Model
{
    protected $fillable = ['mfg_routing_id', 'mfg_work_center_id', 'step_sequence', 'setup_time', 'run_time_per_unit'];

    public function routing(): BelongsTo
    {
        return $this->belongsTo(MfgRouting::class);
    }

    public function workCenter(): BelongsTo
    {
        return $this->belongsTo(MfgWorkCenter::class);
    }
}
