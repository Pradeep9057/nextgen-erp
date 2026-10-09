<?php

namespace App\Modules\Organization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'name',
        'branch_code',
        'address',
        'phone',
        'is_active',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
