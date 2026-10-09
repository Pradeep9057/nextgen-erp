<?php

namespace App\Core\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;

/**
 * BlockchainAdapter handles the communication with the external blockchain network.
 * This is designed as an interface-like service so the underlying chain
 * (Hyperledger, Ethereum, etc.) can be swapped without affecting the Core.
 */
class BlockchainAdapter
{
    protected string $nodeUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->nodeUrl = env('BLOCKCHAIN_NODE_URL', 'https://mainnet.example-chain.io');
        $this->apiKey = env('BLOCKCHAIN_API_KEY', '');
    }

    /**
     * Anchor a hash to the blockchain.
     * Returns the transaction ID (txHash) once confirmed.
     */
    public function anchorHash(string $entityType, int $entityId, string $hash): string
    {
        try {
            // Simulation of a blockchain API call.
            // In a real implementation, this would be a signed request to a node.
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->nodeUrl}/anchor", [
                'entity' => $entityType,
                'id' => $entityId,
                'data_hash' => $hash,
                'timestamp' => now()->toIso8601String(),
            ]);

            if ($response->failed()) {
                throw new Exception("Blockchain node returned error: " . $response->body());
            }

            return $response->json('txHash');
        } catch (Exception $e) {
            Log::error("Blockchain anchoring failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verify a hash against the blockchain.
     */
    public function verifyHash(string $hash): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get("{$this->nodeUrl}/verify", [
                'hash' => $hash
            ]);

            return $response->json('verified') === true;
        } catch (Exception $e) {
            Log::error("Blockchain verification failed: " . $e->getMessage());
            return false;
        }
    }
}
