<?php

namespace App\Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CRM\Services\OpportunityService;
use Illuminate\Http\Request;
use App\Core\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class OpportunityController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected OpportunityService $opportunityService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $opportunities = $this->opportunityService->getAll($request->get('filters', []), $request->get('per_page', 15));
        return $this->successResponse($opportunities);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'crm_account_id' => 'required|exists:crm_accounts,id',
            'organization_id' => 'required|exists:organizations,id',
            'estimated_value' => 'required|numeric',
            'stage' => 'nullable|string',
            'expected_close_date' => 'nullable|date',
        ]);

        try {
            $opportunity = $this->opportunityService->create($validated);
            return $this->successResponse($opportunity, 'Opportunity created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function updateStage(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'stage' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        try {
            $opportunity = $this->opportunityService->updateStage($id, $request->stage, $request->notes);
            return $this->successResponse($opportunity, 'Stage updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function pipelineSummary(): JsonResponse
    {
        $summary = $this->opportunityService->getPipelineSummary();
        return $this->successResponse($summary);
    }
}
