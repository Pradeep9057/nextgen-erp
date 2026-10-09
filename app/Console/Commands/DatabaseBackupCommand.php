<?php

namespace App\Console\Commands;

use App\Core\Services\BackupService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class DatabaseBackupCommand extends Command
{
    protected $signature = 'erp:backup:db';
    protected $description = 'Creates a full PostgreSQL database backup';

    public function handle(BackupService $backupService)
    {
        $this->info('Starting database backup...');

        try {
            $filename = $backupService->createDatabaseBackup();
            $this->info("Backup created: {$filename}");
            Log::info("Automated DB backup successful: {$filename}");
        } catch (Throwable $e) {
            $this->error("Backup failed: " . $e->getMessage());
            Log::error("Automated DB backup failed: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
