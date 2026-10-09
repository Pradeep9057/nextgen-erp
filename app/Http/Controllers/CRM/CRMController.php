<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Models\Account;
use App\Modules\CRM\Services\ViewService;
use Illuminate\Http\Request;

class CRMController extends Controller
{
    public function __construct(protected ViewService $viewService) {}

    public function index(Request $request)
    {
        $orgId = $request->user()->organization_id ?? 1;

        // Use a saved view if provided, otherwise use default
        $view = $request->get('view_id')
            ? \App\Modules\CRM\Models\CrmView::find($request->get('view_id'))
            : $this->viewService->getDefaultView('Lead');

        $leadsQuery = Lead::where('organization_id', $orgId);

        if ($view) {
            $leadsQuery = $this->viewService->applyView($leadsQuery, $view);
        }

        return view('crm.index', [
            'leads' => $leadsQuery->get(),
            'accounts' => Account::where('organization_id', $orgId)->get(),
            'currentView' => $view
        ]);
    }

    public function showLead($id)
    {
        $lead = Lead::findOrFail($id);
        return view('crm.lead-details', ['lead' => $lead]);
    }

    public function showAccount($id)
    {
        $account = Account::findOrFail($id);
        return view('crm.account-details', ['account' => $account]);
    }
}
