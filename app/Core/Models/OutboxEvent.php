<?php

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Model;

class OutboxEvent extends Model
{
    protected $fillable = [
        'event_type',
        'payload',
        'status',
        'attempts',
        'last_error',
        'processed_at'
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
}
