<?php

namespace App\Modules\Customization\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessRule extends Model
{
    protected $fillable = ['module_slug', 'event', 'condition_json', 'action_json', 'is_active'];

    protected $casts = [
        'condition_json' => 'array',
        'action_json' => 'array',
        'is_active' => 'boolean',
    ];
}
