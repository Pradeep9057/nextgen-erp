<?php

namespace App\Core\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class CacheService
{
    /**
     * Cache-aside implementation for retrieving data.
     */
    public function remember(string $key, int $ttl, callable $callback)
    {
        try {
            return Cache::remember($key, $ttl, $callback);
        } catch (Exception $e) {
            Log::warning("Cache failure for key {$key}: " . $e->getMessage());
            // Fallback to database directly
            return $callback();
        }
    }

    /**
     * Explicit invalidation of a key.
     */
    public function forget(string $key): void
    {
        try {
            Cache::forget($key);
        } catch (Exception $e) {
            Log::error("Cache forget failed for key {$key}: " . $e->getMessage());
        }
    }

    /**
     * Generate a scoped cache key.
     */
    public function generateKey(string $module, string $entity, $id = null): string
    {
        $orgId = auth()->user()?->organization_id ?? 'system';
        return "org:{$orgId}:{$module}:{$entity}:" . ($id ?? 'all');
    }
}
