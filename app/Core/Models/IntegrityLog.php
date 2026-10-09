<?php

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrityLog extends Model
{
    protected $fillable = [
        'entity_type',
        'entity_id',
        'hash',
        'previous_hash',
        'timestamp',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];
}
