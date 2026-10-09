<?php

namespace App\Modules\CRM\Services;

use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Models\Account;
use App\Modules\CRM\Models\Opportunity;
use App\Modules\CRM\Models\Contact;
use Illuminate\Support\Facades\DB;

class Entity360Service
{
    /**
     * Get a comprehensive 360 view of a Lead.
     */
    public function getLead360(int $leadId)
    {
        $lead = Lead::findOrFail($leadId);

        return [
            'profile' => $lead,
            'activities' => [], // To be implemented in Activity Timeline
            'metrics' => [
                'lead_score' => $this->calculateLeadScore($lead),
                'engagement_level' => 'Medium',
            ],
            'related' => [
                'opportunities' => Opportunity::where('crm_contact_id', $lead->id)->get(), // Assuming link if lead not converted
            ]
        ];
    }

    /**
     * Get a comprehensive 360 view of an Account.
     */
    public function getAccount360(int $accountId)
    {
        $account = Account::findOrFail($accountId);

        return [
            'profile' => $account,
            'contacts' => $account->contacts,
            'opportunities' => Opportunity::where('crm_account_id', $accountId)->get(),
            'metrics' => [
                'customer_lifetime_value' => $this->calculateCLV($account),
                'health_score' => 'Healthy',
            ],
            'activities' => [], // To be implemented in Activity Timeline
        ];
    }

    protected function calculateLeadScore(Lead $lead): int
    {
        $score = 0;
        if ($lead->status === 'qualified') $score += 50;
        if ($lead->email) $score += 20;
        if ($lead->phone) $score += 20;
        return $score;
    }

    protected function calculateCLV(Account $account): float
    {
        return Opportunity::where('crm_account_id', $account->id)
            ->where('stage', 'Closed Won')
            ->sum('estimated_value');
    }
}
