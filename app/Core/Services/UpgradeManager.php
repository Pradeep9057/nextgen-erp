<?php

namespace App\Core\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Exception;

class UpgradeManager
{
    /**
     * Check for pending migrations and apply them.
     */
    public function runMigrations(): array
    {
        try {
            // Get list of migrations that haven't been run yet
            $pending = DB::table('migrations')
                ->where('batch', '<', (DB::table('migrations')->max('batch') ?? 0) + 1)
                ->get();

            // Execute the artisan migration command
            // --force is required for production environments
            Artisan::call('migrate', ['--force' => true]);

            return [
                'success' => true,
                'message' => 'Migrations applied successfully',
                'timestamp' => now()->toIso8601String()
            ];
        } catch (Exception $e) {
            Log::error("Upgrade failed during migration: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify current system version against target version.
     */
    public function checkVersion(string $targetVersion): bool
    {
        $currentVersion = config('app.version', '1.0.0');
        return version_compare($currentVersion, $targetVersion, '<');
    }

    /**
     * Perform a full system upgrade.
     */
    public function upgradeSystem(string $targetVersion): array
    {
        if (!$this->checkVersion($targetVersion)) {
            return ['success' => true, 'message' => 'System is already up to date.'];
        }

        // 1. Trigger an automatic backup before upgrade
        $backupService = app(BackupService::class);
        $backupFile = $backupService->createDatabaseBackup();

        // 2. Run Migrations
        $migrationResult = $this->runMigrations();

        if (!$migrationResult['success']) {
            return [
                'success' => false,
                'error' => 'Migration failed. Please restore from backup: ' . $backupFile
            ];
        }

        // 3. Clear Caches
        Artisan::call('cache:clear');
        Artisan::call('config:cache');

        return [
            'success' => true,
            'message' => "System upgraded to {$targetVersion}. Backup created: {$backupFile}"
        ];
    }
}
