<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Services\AdminDashboardServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly AdminDashboardServiceInterface $dashboardService
    ) {}

    public function index(): View
    {
        $stats = $this->dashboardService->getStatistics();
        $recentUsers = $this->dashboardService->getRecentUsers(5);
        $recentActivities = $this->dashboardService->getRecentActivities(10);

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentActivities'
        ));
    }
}
