<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $donor = auth()->user();
        $stats = [
            'total_donations' => 0,
            'campaigns_supported' => 0,
        ];
        $recentDonations = collect();
        $recentActivities = collect();
        $recommendedCampaigns = collect();
        $totalDonationAmount = 0;

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
        $donor = auth()->user();
        return view('donor.wallet', compact('donor'));
    }

    public function notifications(): View
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->get();
        return view('donor.notifications', compact('notifications'));
    }

    public function settings(): View
    {
        return view('donor.dashboard');
    }
}
