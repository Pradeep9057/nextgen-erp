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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'module_slug' => 'required|string',
            'field_name' => 'required|string',
            'field_type' => 'required|in:text,number,date,boolean,select',
            'is_required' => 'boolean',
            'options' => 'nullable|array',
        ]);

        $field = CustomField::create($validated);

        return response()->json($field, 201);
    }

    /**
     * Update a value for a specific entity's custom field.
     */
    public function updateValue(Request $request, $entityType, $entityId)
    {
        $request->validate([
            'field_id' => 'required|exists:custom_fields,id',
            'value' => 'required',
        ]);

        $value = CustomFieldValue::updateOrCreate(
            [
                'custom_field_id' => $request->field_id,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
            ],
            ['value' => $request->value]
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