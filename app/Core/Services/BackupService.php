<?php

namespace App\Core\Services;

use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class BackupService
{
    protected string $backupPath = 'backups/db';

    /**
     * Perform a full PostgreSQL database dump.
     */
    public function createDatabaseBackup(): string
    {
        $filename = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';
        $fullPath = storage_path("app/{$this->backupPath}/{$filename}");

        try {
            // Use pg_dump for a reliable, consistent snapshot
            // Note: PGPASSWORD must be configured in the environment
            $process = Process::run("pg_dump -U " . env('DB_USERNAME') . " -h " . env('DB_HOST') . " " . env('DB_DATABASE') . " > {$fullPath}");

            if ($process->failed()) {
                throw new Exception("pg_dump failed: " . $process->errorOutput());
            }

            Log::info("Database backup created successfully: {$filename}");
            return $filename;
        } catch (Exception $e) {
            Log::error("Backup failure: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Restore database from a specific backup file.
     */
    public function restoreDatabase(string $filename): bool
    {
        $fullPath = storage_path("app/{$this->backupPath}/{$filename}");

        if (!file_exists($fullPath)) {
            throw new Exception("Backup file not found: {$filename}");
        }

        try {
            // Use psql to restore the dump
            $process = Process::run("psql -U " . env('DB_USERNAME') . " -h " . env('DB_HOST') . " " . env('DB_DATABASE') . " < {$fullPath}");

            if ($process->failed()) {
                throw new Exception("psql restore failed: " . $process->errorOutput());
            }

            Log::info("Database restored successfully from {$filename}");
            return true;
        } catch (Exception $e) {
            Log::error("Restore failure: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Clean up old backups to save disk space.
     */
    public function purgeOldBackups(int $daysToKeep = 30): void
    {
        $files = Storage::files($this->backupPath);
        $now = now();

        foreach ($files as $file) {
            if ($now->diffInDays(Storage::lastModified($file)) > $daysToKeep) {
                Storage::delete($file);
            }
        }
    }
}
