<?php

namespace App\Core\Services;

use App\Core\Models\OutboxEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class OutboxService
{
    /**
     * Queue an event for external processing.
     * This must be called within the same DB transaction as the business logic.
     */
    public function enqueue(string $eventType, array $payload): void
    {
        OutboxEvent::create([
            'event_type' => $eventType,
            'payload' => $payload,
            'status' => OutboxEvent::STATUS_PENDING,
            'attempts' => 0,
        ]);
    }

    /**
     * Process pending events.
     * This is designed to be called by a background worker (Cron/Queue).
     */
    public function processEvents(callable $handler): void
    {
        $events = OutboxEvent::where('status', OutboxEvent::STATUS_PENDING)
            ->where('attempts', '<', 5)
            ->orderBy('created_at', 'asc')
            ->limit(50)
            ->get();

        foreach ($events as $event) {
            try {
                $event->update(['status' => OutboxEvent::STATUS_PROCESSING]);

                // Execute the external handler (e.g., Blockchain Adapter)
                $handler($event->event_type, $event->payload);

                $event->update([
                    'status' => OutboxEvent::STATUS_COMPLETED,
                    'processed_at' => now(),
                ]);
            } catch (Exception $e) {
                $event->update([
                    'status' => OutboxEvent::STATUS_PENDING,
                    'attempts' => $event->attempts + 1,
                    'last_error' => $e->getMessage(),
                ]);
                Log::error("Outbox event {$event->id} failed: " . $e->getMessage());
            }
        }
    }
}
