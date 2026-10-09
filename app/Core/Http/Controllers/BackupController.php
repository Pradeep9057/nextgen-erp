<?php

namespace App\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Services\BackupService;
use App\Core\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BackupController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected BackupService $backupService
    ) {}

    public function triggerBackup(): JsonResponse
    {
        try {
            $filename = $this->backupService->createDatabaseBackup();
            return $this->successResponse(['filename' => $filename], 'Backup created successfully');
        } catch (\Exception $e) {
            return $this->errorResponse("Backup failed: " . $e->getMessage());
        }
    }

    public function triggerRestore(Request $request): JsonResponse
    {
        $request->validate([
            'filename' => 'required|string'
        ]);

        try {
            $this->backupService->restoreDatabase($request->filename);
            return $this->successResponse(null, 'Database restored successfully');
        } catch (\Exception $e) {
            return $this->errorResponse("Restore failed: " . $e->getMessage());
        }
    }
}
