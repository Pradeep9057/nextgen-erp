<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Models\Account;
use Illuminate\Http\Request;

class CRMController extends Controller
{
    public function index()
    {
        return view('crm.index', [
            'leads' => Lead::all(),
            'accounts' => Account::all()
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
