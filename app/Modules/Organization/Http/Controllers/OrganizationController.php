<?php

namespace App\Modules\Organization\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Organization\Services\OrganizationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrganizationController extends Controller
{
    public function __construct(
        protected OrganizationService $organizationService
    ) {}

    public function index(): JsonResponse
    {
        $organizations = \App\Modules\Organization\Models\Organization::all();
        return response()->json([
            'success' => true,
            'data' => $organizations
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tax_id' => 'nullable|string|unique:organizations,tax_id',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'currency' => 'nullable|string|max:3',
            'default_branch_name' => 'nullable|string'
        ]);

        try {
            $org = $this->organizationService->createOrganization($validated);
            return response()->json([
                'success' => true,
                'data' => $org
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function show($id): JsonResponse
    {
        $org = $this->organizationService->getOrganizationWithBranches((int)$id);
        return response()->json([
            'success' => true,
            'data' => $org
        ]);
    }
}
