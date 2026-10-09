<?php

namespace App\Modules\Customization\Services;

use App\Modules\Customization\Models\CustomModule;
use App\Modules\Customization\Models\CustomField;
use App\Modules\Customization\Models\CustomFieldValue;
use Illuminate\Support\Facades\DB;

class CustomizationService
{
    public function defineField(int $moduleId, array $data): CustomField
    {
        return CustomField::create([
            'custom_module_id' => $moduleId,
            'field_key' => $data['field_key'],
            'label' => $data['label'],
            'type' => $data['type'],
            'validation_rules' => $data['validation_rules'] ?? null,
            'is_required' => $data['is_required'] ?? false,
            'is_searchable' => $data['is_searchable'] ?? false,
            'options' => $data['options'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
    }

    public function saveCustomValues(int $entityId, array $values): void
    {
        DB::transaction(function () use ($entityId, $values) {
            foreach ($values as $fieldId => $value) {
                CustomFieldValue::updateOrCreate(
                    ['custom_field_id' => $fieldId, 'entity_id' => $entityId],
                    ['value' => $value]
                );
            }
        });
    }

    public function getValuesForEntity(int $entityId, int $moduleId): array
    {
        return CustomFieldValue::where('entity_id', $entityId)
            ->whereHas('field', function($q) use ($moduleId) {
                $q->where('custom_module_id', $moduleId);
            })
            ->get()
            ->pluck('value', 'custom_field_id')
            ->toArray();
    }
}
