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
        // Basic Condition Logic: ['field' => 'total', 'operator' => '>', 'value' => 1000]
        $field = $condition['field'];
        $operator = $condition['operator'];
        $expected = $condition['value'];
        $actual = $data[$field] ?? null;

        return match ($operator) {
            '>' => $actual > $expected,
            '<' => $actual < $expected,
            '==' => $actual == $expected,
            '!=' => $actual != $expected,
            'contains' => str_contains($actual, $expected),
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
