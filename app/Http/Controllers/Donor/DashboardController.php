<?php

namespace App\Http\Controllers\Donor;

use App\Contracts\Services\DonorDashboardServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DonorDashboardServiceInterface $dashboardService
    ) {}

    public function index(): View
    {
        $donor = auth()->user();
        
        $stats = $this->dashboardService->getStatistics($donor);
        $recentDonations = $this->dashboardService->getRecentDonations($donor, 5);
        $recentActivities = $this->dashboardService->getRecentActivities($donor, 10);
        $recommendedCampaigns = $this->dashboardService->getRecommendedCampaigns(5);
        $totalDonationAmount = $this->dashboardService->getTotalDonationAmount($donor);

        return view('donor.dashboard', compact(
            'donor',
            'stats',
            'recentDonations',
            'recentActivities',
            'recommendedCampaigns',
            'totalDonationAmount'
        ));
    }
}