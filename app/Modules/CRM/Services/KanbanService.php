<?php

namespace App\Modules\CRM\Services;

use App\Modules\CRM\Models\Opportunity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class KanbanService
{
    /**
     * Update opportunity stage via drag-and-drop.
     */
    public function moveOpportunity(int $opportunityId, string $newStage, int $orgId): bool
    {
        return DB::transaction(function () use ($opportunityId, $newStage, $orgId) {
            $opportunity = Opportunity::where('id', $opportunityId)
                ->where('organization_id', $orgId)
                ->lockForUpdate()
                ->firstOrFail();

            $oldStage = $opportunity->stage;

            if ($oldStage === $newStage) {
                return true;
            }

            $opportunity->update([
                'stage' => $newStage,
                // Probability can be automatically adjusted based on stage
                'probability' => $this->getProbabilityForStage($newStage),
            ]);

            // Record in history for sales velocity tracking
            $opportunity->history()->create([
                'from_stage' => $oldStage,
                'to_stage' => $newStage,
                'changed_at' => now(),
            ]);

            return true;
        });
    }

    /**
     * Probability mapping for standard pipeline stages.
     */
    protected function getProbabilityForStage(string $stage): int
    {
        $map = [
            'Discovery' => 10,
            'Qualification' => 25,
            'Proposal' => 50,
            'Negotiation' => 75,
            'Closed Won' => 100,
            'Closed Lost' => 0,
        ];

        return $map[$stage] ?? 20;
    }

    /**
     * Get the canonical list of pipeline stages.
     */
    public function getPipelineStages(): array
    {
        return [
            'Discovery',
            'Qualification',
            'Proposal',
            'Negotiation',
            'Closed Won',
            'Closed Lost',
        ];
    }
}
