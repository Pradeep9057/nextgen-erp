<?php

namespace App\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Filterable
{
    public function scopeApplyFilters(Builder $query, Request $request)
    {
        $filters = $request->get('filters', []);

        foreach ($filters as $field => $value) {
            if (empty($value)) continue;

            // Handle range filters (e.g., created_at_from, created_at_to)
            if (str_ends_with($field, '_from')) {
                $column = str_replace('_from', '', $field);
                $query->where($column, '>=', $value);
            } elseif (str_ends_with($field, '_to')) {
                $column = str_replace('_to', '', $field);
                $query->where($column, '<=', $value);
            } elseif (str_contains($field, 'search')) {
                // Simple global search logic
                $query->where(function($q) use ($value) {
                    $q->where('name', 'like', "%{$value}%")
                      ->orWhere('email', 'like', "%{$value}%");
                });
            } else {
                $query->where($field, $value);
            }
        }

        return $query;
    }

    public function scopeApplySorting(Builder $query, Request $request)
    {
        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('direction', 'desc');

        return $query->orderBy($sortField, $sortDir);
    }
}
