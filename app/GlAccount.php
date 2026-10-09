<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GlAccount extends Model
{
    protected $fillable = ['organization_id', 'code', 'name', 'type', 'balance'];

    public function entries(): HasMany
    {
        return $this->hasMany(GlEntry::class);
    }
}
