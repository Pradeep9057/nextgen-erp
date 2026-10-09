<?php

namespace App\Core\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class IntegrityService
{
    /**
     * Generate a canonical hash for a given record.
     * Ensures that the same data always produces the same hash.
     */
    public function generateHash(array $data): string
    {
        // 1. Canonicalization: Sort keys alphabetically to ensure consistency
        ksort($data);

        // 2. Serialization: Convert to a stable JSON string
        $canonicalString = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // 3. Hashing: SHA-256
        return hash('sha256', $canonicalString);
    }

    /**
     * Verify a record's current state against a stored hash.
     */
    public function verifyIntegrity(array $currentData, string $storedHash): bool
    {
        $currentHash = $this->generateHash($currentData);
        return hash_equals($storedHash, $currentHash);
    }

    /**
     * Create a hash-linked event (Chain).
     * Every record includes the hash of the previous record.
     */
    public function createLinkedHash(array $data, ?string $previousHash): string
    {
        $data['_prev_hash'] = $previousHash;
        return $this->generateHash($data);
    }
}
