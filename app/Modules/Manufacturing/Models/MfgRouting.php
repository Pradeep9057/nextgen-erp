<?php

namespace App\Modules\Manufacturing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MfgRouting extends Model
{
    protected $fillable = ['mfg_bom_id', 'routing_name', 'is_active'];

    public function steps(): HasMany
    {
        return $this->hasMany(MfgRoutingStep::class)->orderBy('step_sequence');
    }

    public function bom(): BelongsTo
    {
        return $this->belongsTo(MfgBom::class);
    }
}
