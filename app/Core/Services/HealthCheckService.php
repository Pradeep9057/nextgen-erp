<?php

namespace App\Core\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class HealthCheckService
{
    /**
     * Perform a comprehensive health check of all critical dependencies.
     */
    public function checkAll(): array
    {
        $results = [
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String(),
            'dependencies' => []
        ];

        // 1. Database Check
        try {
            DB::connection()->getPdo();
            $results['dependencies']['database'] = ['status' => 'up', 'latency' => $this->measureLatency(fn() => DB::select('SELECT 1'))];
        } catch (Exception $e) {
            $results['dependencies']['database'] = ['status' => 'down', 'error' => $e->getMessage()];
            $results['status'] = 'unhealthy';
        }

        // 2. Cache Check (Valkey)
        try {
            Cache::store('valkey')->get('health_check_ping');
            $results['dependencies']['cache'] = ['status' => 'up', 'latency' => $this->measureLatency(fn() => Cache::store('valkey')->put('health_check_ping', true, 10))];
        } catch (Exception $e) {
            $results['dependencies']['cache'] = ['status' => 'down', 'error' => $e->getMessage()];
            // Cache is often optional, so we might keep status 'healthy' but warn
        }

        // 3. Queue Check
        try {
            // Check if queue worker is processing (simple check for pending jobs)
            $pending = DB::table('jobs')->count();
            $results['dependencies']['queue'] = ['status' => 'up', 'pending_jobs' => $pending];
        } catch (Exception $e) {
            $results['dependencies']['queue'] = ['status' => 'down', 'error' => $e->getMessage()];
            $results['status'] = 'unhealthy';
        }

        return $results;
    }

    private function measureLatency(callable $callback): float
    {
        $start = microtime(true);
        $callback();
        return round((microtime(true) - $start) * 1000, 2); // ms
    }
}
