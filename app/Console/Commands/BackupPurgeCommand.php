<?php

namespace App\Console\Commands;

use App\Core\Services\BackupService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class BackupPurgeCommand extends Command
{
    protected $signature = 'erp:backup:purge';
    protected $description = 'Purges old database backups';

    public function handle(BackupService $backupService)
    {
        $this->info('Purging old backups...');

        try {
            $backupService->purgeOldBackups(30);
            $this->info('Old backups purged successfully.');
        } catch (Throwable $e) {
            $this->error("Purge failed: " . $e->getMessage());
            Log::error("Backup purge failed: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
