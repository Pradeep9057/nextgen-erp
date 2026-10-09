<?php

namespace App\Core\Services;

use App\Core\Models\IntegrityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IntegrityManager
{
    public function __construct(protected IntegrityService $integrityService) {}

    /**
     * Seal a record by recording its hash in the integrity log.
     */
    public function sealRecord(string $entityType, int $entityId, array $data): string
    {
        return DB::transaction(function () use ($entityType, $entityId, $data) {
            // Get the last hash for this entity to create a chain
            $prevHash = IntegrityLog::where('entity_type', $entityType)
                ->where('entity_id', $entityId)
                ->orderBy('id', 'desc')
                ->value('hash');

            $hash = $this->integrityService->createLinkedHash($data, $prevHash);

            IntegrityLog::create([
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'hash' => $hash,
                'previous_hash' => $prevHash,
                'timestamp' => now(),
                'metadata' => ['action' => 'seal']
            ]);

            return $hash;
        });
    }

    /**
     * Verify the entire chain of a record.
     */
    public function verifyChain(string $entityType, int $entityId): bool
    {
        $logs = IntegrityLog::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->orderBy('id', 'asc')
            ->get();

        $expectedPrevHash = null;
        foreach ($logs as $log) {
            if ($log->previous_hash !== $expectedPrevHash) {
                return false;
            }
            $expectedPrevHash = $log->hash;
        }

        return true;
    }
}
