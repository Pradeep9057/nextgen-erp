<?php

namespace App\Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Sales\Services\ApprovalService;
use Illuminate\Http\Request;
use App\Core\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ApprovalController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ApprovalService $approvalService
    ) {}

    public function request(Request $request, int $orderId): JsonResponse
    {
        $request->validate(['comments' => 'nullable|string']);

        try {
            $approval = $this->approvalService->requestApproval($orderId, $request->comments);
            return $this->successResponse($approval, 'Approval requested successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function resolve(Request $request, int $approvalId): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|in:Approved,Rejected',
            'comments' => 'nullable|string'
        ]);

        try {
            $approval = $this->approvalService->processApproval($approvalId, $request->status, $request->comments);
            return $this->successResponse($approval, 'Approval processed successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
