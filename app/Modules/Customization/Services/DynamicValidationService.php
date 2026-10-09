<?php

namespace App\Modules\Customization\Services;

use Illuminate\Support\Facades\Validator;
use App\Modules\Customization\Models\CustomField;
use Exception;

class DynamicValidationService
{
    /**
     * Validate data based on custom field definitions.
     */
    public function validateCustomData(int $moduleId, array $data): array
    {
        $fields = CustomField::where('custom_module_id', $moduleId)->get();
        $rules = [];
        $messages = [];

        foreach ($fields as $field) {
            $ruleString = $field->is_required ? 'required' : 'nullable';
            if ($field->validation_rules) {
                $ruleString .= '|' . $field->validation_rules;
            }

            $rules[$field->id] = $ruleString;
            $messages[$field->id->required] = "The {$field->label} field is required.";
        }

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new Exception("Custom validation failed: " . implode(', ', $validator->errors()->all()));
        }

        return $validator->validated();
    }
}
