<?php

namespace App\Core\Services;

use App\GlAccount;
use App\GlEntry;
use Illuminate\Support\Facades\DB;
use Exception;

class GlService
{
    /**
     * Post a double-entry transaction to the General Ledger.
     *
     * @param array $entries Array of [account_id, debit, credit, description]
     */
    public function postTransaction(int $orgId, array $entries, string $refType, int $refId): void
    {
        DB::transaction(function () use ($orgId, $entries, $refType, $refId) {
            $totalDebit = 0;
            $totalCredit = 0;

            foreach ($entries as $entry) {
                $totalDebit += $entry['debit'] ?? 0;
                $totalCredit += $entry['credit'] ?? 0;
            }

            if (abs($totalDebit - $totalCredit) > 0.001) {
                throw new Exception("General Ledger imbalance: Debits ({$totalDebit}) must equal Credits ({$totalCredit}).");
            }

            foreach ($entries as $entry) {
                // 1. Create the ledger entry
                GlEntry::create([
                    'organization_id' => $orgId,
                    'gl_account_id' => $entry['account_id'],
                    'debit' => $entry['debit'] ?? 0,
                    'credit' => $entry['credit'] ?? 0,
                    'reference_type' => $refType,
                    'reference_id' => $refId,
                    'entry_date' => now(),
                    'description' => $entry['description'] ?? '',
                ]);

                // 2. Update the account balance
                $account = GlAccount::findOrFail($entry['account_id']);

                // For Assets/Expenses: Debit increases, Credit decreases
                // For Liabilities/Equity/Revenue: Credit increases, Debit decreases
                $balanceChange = ($entry['debit'] ?? 0) - ($entry['credit'] ?? 0);

                // Simple balance update (simplified for this implementation)
                $account->increment('balance', $balanceChange);
            }
        });
    }
}