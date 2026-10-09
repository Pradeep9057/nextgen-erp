<?php

namespace App\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Services\IntegrityManager;
use App\Core\Services\BlockchainAdapter;
use App\Core\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected IntegrityManager $integrityManager,
        protected BlockchainAdapter $blockchainAdapter
    ) {}

    /**
     * Verify a specific record's integrity.
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'entity_type' => 'required|string',
            'entity_id' => 'required|integer',
            'current_data' => 'required|array',
        ]);

        $entityType = $request->entity_type;
        $entityId = (int)$request->entity_id;
        $data = $request->current_data;

        // 1. Local Chain Verification
        // Checks if the history of hashes is unbroken
        $isChainValid = $this->integrityManager->verifyChain($entityType, $entityId);

        // 2. Immediate State Verification
        // Recalculates hash of provided data and compares to the latest local record
        $currentHash = app(\App\Core\Services\IntegrityService::class)->generateHash($data);

        $latestLocalLog = \App\Core\Models\IntegrityLog::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->orderBy('id', 'desc')
            ->first();

        $localMatch = $latestLocalLog && hash_equals($latestLocalLog->hash, $currentHash);

        // 3. Blockchain Verification
        // Checks if the latest local hash is anchored on the blockchain
        $blockchainVerified = false;
        if ($latestLocalLog) {
            $blockchainVerified = $this->blockchainAdapter->verifyHash($latestLocalLog->hash);
        }

        return $this->successResponse([
            'entity' => "{$entityType}:{$entityId}",
            'verification' => [
                'local_chain_intact' => $isChainValid,
                'data_matches_latest_seal' => $localMatch,
                'blockchain_anchored' => $blockchainVerified,
            ],
            'verdict' => ($isChainValid && $localMatch && $blockchainVerified) ? 'VERIFIED' : 'TAMPERED_OR_UNVERIFIED',
            'details' => [
                'current_hash' => $currentHash,
                'latest_seal_hash' => $latestLocalLog?->hash,
            ]
        ]);
    }
}
