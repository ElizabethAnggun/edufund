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

    public function wallet(): View
    {
        $user = auth()->user();
        $balance = $user->donations()->where('status', 'completed')->sum('amount');
        $walletAddress = $user->wallet_address;
        return view('donor.wallet', compact('balance', 'walletAddress'));
    }

    public function notifications(): View
    {
        $user = auth()->user();
        $notifications = collect(); // Placeholder
        return view('donor.notifications', compact('notifications'));
    }

    public function settings(): View
    {
        $settings = [
            'email_notifications' => true,
            'marketing_emails' => false,
        ];
        return view('donor.settings', compact('settings'));
    }
}
