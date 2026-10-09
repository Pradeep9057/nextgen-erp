<?php

namespace App\Http\Controllers;

use App\Core\Services\GlService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FinancialReportController extends Controller
{
    public function __construct(protected GlService $glService) {}

    public function trialBalance(Request $request)
    {
        $orgId = $request->user()->organization_id ?? 1;
        return response()->json([
            'report' => 'Trial Balance',
            'data' => $this->glService->getTrialBalance($orgId)
        ]);
    }

    public function profitAndLoss(Request $request)
    {
        $orgId = $request->user()->organization_id ?? 1;
        return response()->json([
            'report' => 'Profit & Loss',
            'data' => $this->glService->getProfitAndLoss($orgId)
        ]);
    }
}