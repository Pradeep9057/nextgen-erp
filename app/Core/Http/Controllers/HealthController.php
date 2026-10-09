<?php

namespace App\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Services\HealthCheckService;
use App\Core\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected HealthCheckService $healthService
    ) {}

    public function check(): JsonResponse
    {
        $status = $this->healthService->checkAll();
        $code = ($status['status'] === 'healthy') ? 200 : 503;

        return response()->json($status, $code);
    }
}
