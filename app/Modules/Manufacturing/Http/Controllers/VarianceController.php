<?php

namespace App\Modules\Manufacturing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Manufacturing\Services\VarianceService;
use App\Core\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class VarianceController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected VarianceService $varianceService
    ) {}

    public function show(int $id): JsonResponse
    {
        try {
            $variance = $this->varianceService->getOrderVariance($id);
            return $this->successResponse($variance);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
