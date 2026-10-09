<?php

namespace App\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Services\UpgradeManager;
use App\Core\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UpgradeController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected UpgradeManager $upgradeManager
    ) {}

    public function check(Request $request): JsonResponse
    {
        $targetVersion = $request->get('version', '1.0.0');
        $needsUpgrade = $this->upgradeManager->checkVersion($targetVersion);

        return $this->successResponse([
            'current_version' => config('app.version', '1.0.0'),
            'target_version' => $targetVersion,
            'needs_upgrade' => $needsUpgrade
        ]);
    }

    public function apply(Request $request): JsonResponse
    {
        $request->validate([
            'version' => 'required|string'
        ]);

        $result = $this->upgradeManager->upgradeSystem($request->version);

        if ($result['success']) {
            return $this->successResponse($result);
        }

        return $this->errorResponse($result['error']);
    }
}
