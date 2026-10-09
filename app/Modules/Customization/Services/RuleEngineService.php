<?php

namespace App\Modules\Customization\Services;

use App\Modules\Customization\Models\BusinessRule;
use Illuminate\Support\Facades\Log;

class RuleEngineService
{
    /**
     * Evaluate rules for a specific event in a module.
     */
    public function evaluate(string $moduleSlug, string $event, array $data): void
    {
        $rules = BusinessRule::where('module_slug', $moduleSlug)
            ->where('event', $event)
            ->where('is_active', true)
            ->get();

        foreach ($rules as $rule) {
            if ($this->checkCondition($rule->condition_json, $data)) {
                $this->executeAction($rule->action_json, $data);
            }
        }
    }

    protected function checkCondition(array $condition, array $data): bool
    {
        // Support for nested logical groups: ['type' => 'AND', 'conditions' => [...]]
        if (isset($condition['type'])) {
            $type = $condition['type'];
            $conditions = $condition['conditions'];

            if ($type === 'AND') {
                foreach ($conditions as $subCondition) {
                    if (!$this->checkCondition($subCondition, $data)) return false;
                }
                return true;
            }

            if ($type === 'OR') {
                foreach ($conditions as $subCondition) {
                    if ($this->checkCondition($subCondition, $data)) return true;
                }
                return false;
            }
        }

        // Basic Condition Logic: ['field' => 'total', 'operator' => '>', 'value' => 1000]
        $field = $condition['field'] ?? null;
        $operator = $condition['operator'] ?? null;
        $expected = $condition['value'] ?? null;
        $actual = $data[$field] ?? null;

        if ($field === null || $operator === null) return false;

        return match ($operator) {
            '>' => $actual > $expected,
            '<' => $actual < $expected,
            '==' => $actual == $expected,
            '!=' => $actual != $expected,
            'contains' => str_contains((string)$actual, (string)$expected),
            'in' => in_array($actual, (array)$expected),
            default => false,
        };
    }

    protected function executeAction(array $action, array $data): void
    {
        $type = $action['type'];

        switch ($type) {
            case 'notification':
                Log::info("Rule Triggered: Notification sent to {$action['recipient']} for record {$data['id']}");
                break;
            case 'update_field':
                Log::info("Rule Triggered: Updating field {$action['field']} to {$action['value']}");
                break;
            default:
                Log::warning("Unknown rule action type: {$type}");
        }
    }
}
