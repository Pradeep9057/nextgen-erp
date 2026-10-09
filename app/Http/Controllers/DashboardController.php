<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Core\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function index()
    {
        return view('dashboard', [
            'kpis' => $this->dashboardService->getKpis()
        ]);
    }
}