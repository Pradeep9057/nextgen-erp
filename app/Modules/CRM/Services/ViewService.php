<?php

namespace App\Modules\CRM\Services;

use App\Modules\CRM\Models\CrmView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ViewService
{
    /**
     * Apply a saved view to a query.
     */
    public function applyView(Builder $query, CrmView $view): Builder
    {
        // 1. Apply Filters
        if (!empty($view->filters)) {
            foreach ($view->filters as $column => $value) {
                if ($value === null) continue;

                if (is_array($value)) {
                    $query->whereIn($column, $value);
                } else {
                    $query->where($column, $value);
                }
            }
        }

        // 2. Apply Sorting
        if ($view->sort_by) {
            $query->orderBy($view->sort_by, $view->sort_order ?? 'asc');
        }

        return $query;
    }

    /**
     * Get default view for a user and entity.
     */
    public function getDefaultView(string $entityType): ?CrmView
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        return CrmView::where('organization_id', $user->organization_id)
            ->where('entity_type', $entityType)
            ->where('is_default', true)
            ->first();
    }
}
