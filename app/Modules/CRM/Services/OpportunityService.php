<?php

namespace App\Modules\CRM\Services;

use App\Modules\CRM\Models\Opportunity;
use App\Modules\CRM\Models\OpportunityHistory;
use App\Core\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OpportunityService extends BaseService
{
    public function __construct(\App\Modules\CRM\Models\Opportunity $opportunityModel)
    {
        parent::__construct($opportunityModel);
    }

    public function updateStage(int $opportunityId, string $newStage, ?string $notes = null): Opportunity
    {
        return DB::transaction(function () use ($opportunityId, $newStage, $notes) {
            $opportunity = Opportunity::findOrFail($opportunityId);
            $oldStage = $opportunity->stage;

            if ($oldStage !== $newStage) {
                OpportunityHistory::create([
                    'crm_opportunity_id' => $opportunity->id,
                    'from_stage' => $oldStage,
                    'to_stage' => $newStage,
                    'changed_at' => now(),
                    'notes' => $notes,
                    'changed_by' => Auth::id() ?? 1, // Fallback for dev
                ]);

                $opportunity->update(['stage' => $newStage]);
            }

            return $opportunity;
        });
    }

    public function getPipelineSummary()
    {
        return Opportunity::query()
            ->selectRaw('stage, sum(estimated_value) as total_value, count(*) as count')
            ->groupBy('stage')
            ->get();
    }
}
