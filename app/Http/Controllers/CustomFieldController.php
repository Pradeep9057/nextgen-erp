<?php

namespace App\Http\Controllers;

use App\Modules\Customization\Models\CustomField;
use App\Modules\Customization\Models\CustomFieldValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomFieldController extends Controller
{
    /**
     * Store a new custom field definition.
     */
    public function store(CustomFieldStoreRequest $request)
    {
        $validated = $request->validated();

        $field = CustomField::create($validated);

        return response()->json($field, 201);
    }

    /**
     * Update a value for a specific entity's custom field.
     */
    public function updateValue(CustomFieldValueUpdateRequest $request, $entityType, $entityId)
    {
        $validated = $request->validated();

        $value = CustomFieldValue::updateOrCreate(
            [
                'custom_field_id' => $validated['field_id'],
                'entity_type' => $entityType,
                'entity_id' => $entityId,
            ],
            ['value' => $validated['value']]
        );

        return response()->json($value);
    }

    /**
     * Retrieve all custom values for a specific entity.
     */
    public function getValues($entityType, $entityId)
    {
        $values = CustomFieldValue::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->with('field')
            ->get();

        return response()->json($values);
    }
}