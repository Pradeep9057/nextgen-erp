<?php

namespace App\Modules\Manufacturing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MfgWorkCenter extends Model
{
    protected $fillable = ['organization_id', 'name', 'code', 'hourly_cost', 'capacity_per_hour', 'is_active'];

    public function routingSteps(): HasMany
    {
        return $this->hasMany(MfgRoutingStep::class);
    }
}
