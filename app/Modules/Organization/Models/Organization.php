<?php

namespace App\Modules\Organization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use SoftDeletes;

    protected $table = 'organizations';
    protected $fillable = [
        'name',
        'tax_id',
        'email',
        'phone',
        'address',
        'currency',
        'is_active',
    ];

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }
}
