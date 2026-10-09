<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Modules\CRM\Models\Opportunity;
use App\Modules\CRM\Services\KanbanService;
use Illuminate\Http\Request;

class KanbanController extends Controller
{
    public function __construct(protected KanbanService $kanbanService) {}

    public function index(Request $request)
    {
        $orgId = $request->user()->organization_id ?? 1;
        $stages = $this->kanbanService->getPipelineStages();

        $opportunities = Opportunity::where('organization_id', $orgId)->get();

        // Group opportunities by stage for the frontend
        $board = [];
        foreach ($stages as $stage) {
            $board[$stage] = $opportunities->where('stage', $stage)->values();
        }

        return view('crm.kanban', [
            'board' => $board,
            'stages' => $stages
        ]);
    }

    public function move(Request $request)
    {
        $request->validate([
            'opportunity_id' => 'required|integer',
            'new_stage' => 'required|string',
        ]);

        $orgId = $request->user()->organization_id ?? 1;

        try {
            $this->kanbanService->moveOpportunity(
                $request->opportunity_id,
                $request->new_stage,
                $orgId
            );
            return response()->json(['status' => 'success', 'message' => 'Opportunity moved successfully']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
