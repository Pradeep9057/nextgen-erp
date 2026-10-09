<?php

namespace App\Modules\CRM\Services;

use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Models\Account;
use App\Modules\CRM\Models\Contact;
use App\Core\Services\BaseService;
use Illuminate\Support\Facades\DB;

class CRMService extends BaseService
{
    public function __construct(\App\Modules\CRM\Models\Lead $leadModel)
    {
        parent::__construct($leadModel);
    }

    /**
     * Convert a Lead into an Account and a Contact.
     */
    public function convertLeadToAccount(int $leadId): array
    {
        return DB::transaction(function () use ($leadId) {
            $lead = Lead::findOrFail($leadId);

            // 1. Create Account
            $account = Account::create([
                'organization_id' => $lead->organization_id,
                'name' => $lead->company_name ?? 'Individual Account',
                'industry' => null, // To be filled later
                'website' => null,
                'address' => null,
            ]);

            // 2. Create Contact
            $contact = Contact::create([
                'crm_account_id' => $account->id,
                'first_name' => $lead->first_name,
                'last_name' => $lead->last_name,
                'email' => $lead->email,
                'phone' => $lead->phone,
            ]);

            // 3. Mark lead as converted
            $lead->update(['status' => 'Converted']);

            return [
                'account' => $account,
                'contact' => $contact
            ];
        });
    }
}
