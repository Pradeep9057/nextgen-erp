<?php

namespace App\Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CRM\Services\CRMService;
use Illuminate\Http\Request;
use App\Core\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected CRMService $crmService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $leads = $this->crmService->getAll($request->get('filters', []), $request->get('per_page', 15));
        return $this->successResponse($leads);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'company_name' => 'nullable|string',
            'source' => 'nullable|string',
            'organization_id' => 'required|exists:organizations,id'
        ]);

        try {
            $lead = $this->crmService->create($validated);
            return $this->successResponse($lead, 'Lead created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function convert(int $id): JsonResponse
    {
        try {
            $result = $this->crmService->convertLeadToAccount($id);
            return $this->successResponse($result, 'Lead successfully converted to Account and Contact');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
