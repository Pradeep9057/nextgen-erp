<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Models\CrmView;
use App\Modules\CRM\Services\ViewService;
use Illuminate\Http\Request;

class CRMViewController extends Controller
{
    public function __construct(protected ViewService $viewService) {}

    public function index(Request $request)
    {
        $orgId = $request->user()->organization_id ?? 1;
        $entityType = $request->get('entity', 'Lead');

        $views = CrmView::where('organization_id', $orgId)
            ->where('entity_type', $entityType)
            ->get();

        return response()->json($views);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'entity_type' => 'required|string',
            'filters' => 'nullable|array',
            'sort_by' => 'nullable|string',
            'sort_order' => 'nullable|in:asc,desc',
            'columns' => 'nullable|array',
        ]);

        $view = CrmView::create([
            'organization_id' => $request->user()->organization_id ?? 1,
            'user_id' => $request->user()->id ?? 1,
            'name' => $request->name,
            'entity_type' => $request->entity_type,
            'filters' => $request->filters,
            'sort_by' => $request->sort_by,
            'sort_order' => $request->sort_order,
            'columns' => $request->columns,
            'is_default' => $request->is_default ?? false,
        ]);

        return response()->json($view);
    }

    public function update(Request $request, CrmView $view)
    {
        $view->update($request->all());
        return response()->json($view);
    }

    public function destroy(CrmView $view)
    {
        $view->delete();
        return response()->json(['message' => 'View deleted']);
    }
}
