<?php

namespace App\Core\Console\Commands;

use Illuminate\Console\Command;
use App\Core\Services\OutboxService;
use App\Core\Services\BlockchainAdapter;
use Illuminate\Support\Facades\Log;

class ProcessOutboxCommand extends Command
{
    protected $signature = 'erp:process-outbox';
    protected $description = 'Processes pending outbox events for blockchain anchoring';

    public function handle(OutboxService $outbox, BlockchainAdapter $blockchain)
    {
        $this->info('Processing outbox events...');

        $outbox->processEvents(function ($eventType, $payload) use ($blockchain) {
            if ($eventType === 'INTEGRITY_SEAL') {
                $this->info("Anchoring entity {$payload['entity_id']} to blockchain...");

                $txHash = $blockchain->anchorHash(
                    $payload['entity_type'],
                    $payload['entity_id'],
                    $payload['hash']
                );

                $this->info("Successfully anchored. Tx: {$txHash}");
            } else {
                Log::warning("Unknown event type in outbox: {$eventType}");
            }
        });

        $this->info('Outbox processing complete.');
    }
}
