<?php

namespace App\Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CRM\Services\CRMService;
use App\Modules\CRM\Models\Account;
use App\Modules\CRM\Models\Contact;
use Illuminate\Http\Request;
use App\Core\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected CRMService $crmService
    ) {}

    public function index(Request $request): JsonResponse
    {
        // Using the base service logic via a temporary instance or modifying CRMService
        // For now, we'll use the model directly to demonstrate the Filterable trait
        $accounts = Account::applyFilters($request)
            ->applySorting($request)
            ->paginate($request->get('per_page', 15));

        return $this->successResponse($accounts);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'organization_id' => 'required|exists:organizations,id',
            'industry' => 'nullable|string',
            'website' => 'nullable|url',
            'tax_id' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        try {
            $account = Account::create($validated);
            return $this->successResponse($account, 'Account created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function show(int $id): JsonResponse
    {
        $account = Account::with('contacts')->findOrFail($id);
        return $this->successResponse($account);
    }
}
