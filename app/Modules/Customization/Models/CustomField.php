<?php

namespace App\Modules\Customization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomField extends Model
{
    protected $fillable = ['custom_module_id', 'field_key', 'label', 'type', 'validation_rules', 'is_required', 'is_searchable', 'options', 'sort_order'];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_searchable' => 'boolean',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(CustomModule::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(CustomFieldValue::class);
    }
}
