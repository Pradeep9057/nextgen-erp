<?php

namespace App\Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CRM\Models\Contact;
use Illuminate\Http\Request;
use App\Core\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $contacts = Contact::applyFilters($request)
            ->applySorting($request)
            ->paginate($request->get('per_page', 15));

        return $this->successResponse($contacts);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'crm_account_id' => 'required|exists:crm_accounts,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:crm_contacts,email',
            'phone' => 'nullable|string',
            'job_title' => 'nullable|string',
        ]);

        try {
            $contact = Contact::create($validated);
            return $this->successResponse($contact, 'Contact created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
