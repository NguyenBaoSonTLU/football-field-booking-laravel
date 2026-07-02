<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService)
    {
    }

    public function index(Request $request): View
    {
        $days = $request->integer('days', 30);
        $days = in_array($days, [30, 90], true) ? $days : 30;
        $data = $this->dashboardService->summary($days);

        return view('admin.dashboard.index', compact('data', 'days'));
    }
}
