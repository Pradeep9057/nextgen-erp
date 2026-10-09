<?php

namespace App\Modules\Customization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomModule extends Model
{
    protected $fillable = ['name', 'slug', 'display_name', 'description', 'is_active'];

    public function fields(): HasMany
    {
        return $this->hasMany(CustomField::class);
    }
}
