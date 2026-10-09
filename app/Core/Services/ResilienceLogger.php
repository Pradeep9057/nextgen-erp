<?php

namespace App\Core\Services;

use Illuminate\Support\Facades\Log;
use Throwable;

class ResilienceLogger
{
    /**
     * Log a critical error with full context.
     */
    public static function logCritical(Throwable $e, array $context = []): void
    {
        $orgId = auth()->user()->organization_id ?? 'unknown';
        $userId = auth()->id() ?? 'system';

        Log::critical($e->getMessage(), array_merge([
            'organization_id' => $orgId,
            'user_id' => $userId,
            'trace' => $e->getTraceAsString(),
            'url' => request()->fullUrl(),
            'input' => request()->except(['password', 'password_confirmation']),
        ], $context));
    }

    /**
     * Log a transactional error specifically for ledger/financials.
     */
    public static function logTransactionError(string $message, array $txData, Throwable $e = null): void
    {
        Log::error("Financial Transaction Error: {$message}", [
            'tx_data' => $txData,
            'exception' => $e ? $e->getMessage() : 'N/A',
            'trace' => $e ? $e->getTraceAsString() : null,
        ]);
    }
}
