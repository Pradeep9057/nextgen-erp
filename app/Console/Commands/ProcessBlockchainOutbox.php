<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Core\Services\OutboxService;
use App\Core\Services\BlockchainAdapter;
use Illuminate\Support\Facades\Log;

class ProcessBlockchainOutbox extends Command
{
    protected $signature = 'blockchain:sync';
    protected $description = 'Processes the transactional outbox and anchors integrity hashes to the blockchain';

    public function handle(OutboxService $outbox, BlockchainAdapter $blockchain): int
    {
        $this->info('Starting blockchain synchronization...');

        $outbox->processEvents(function (string $eventType, array $payload) use ($blockchain) {
            if ($eventType === 'integrity.seal') {
                $this->info("Anchoring hash for {$payload['entity_type']} ID {$payload['entity_id']}...");

                $txHash = $blockchain->anchorHash(
                    $payload['entity_type'],
                    $payload['entity_id'],
                    $payload['hash']
                );

                $this->info("Confirmed! txHash: {$txHash}");
            } else {
                $this->warn("Unknown event type: {$eventType}");
            }
        });

        $this->info('Blockchain synchronization complete.');
        return Command::SUCCESS;
    }
}