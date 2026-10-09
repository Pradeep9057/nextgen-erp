<?php

namespace App\Modules\Customization\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Customization\Services\CustomizationService;
use App\Modules\Customization\Models\CustomModule;
use App\Modules\Customization\Models\CustomField;
use Illuminate\Http\Request;
use App\Core\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CustomizationStudioController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected CustomizationService $customizationService
    ) {}

    public function getModuleConfiguration(string $slug): JsonResponse
    {
        $module = CustomModule::where('slug', $slug)->firstOrFail();
        $fields = $module->fields()->orderBy('sort_order')->get();

        return $this->successResponse([
            'module' => $module,
            'fields' => $fields
        ]);
    }

    public function addField(Request $request, string $slug): JsonResponse
    {
        $request->validate([
            'label' => 'required|string',
            'type' => 'required|string|in:text,number,date,select,boolean',
            'field_key' => 'required|string',
            'is_required' => 'boolean',
            'options' => 'nullable|array'
        ]);

        $module = CustomModule::where('slug', $slug)->firstOrFail();

        try {
            $field = $this->customizationService->defineField($module->id, $request->all());
            return $this->successResponse($field, 'Field added successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function updateField(Request $request, int $fieldId): JsonResponse
    {
        $field = CustomField::findOrFail($fieldId);
        $field->update($request->all());

        return $this->successResponse($field, 'Field updated successfully');
    }
}
