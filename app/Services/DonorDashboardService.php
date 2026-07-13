<?php

namespace App\Services;

use App\Contracts\Services\DonorDashboardServiceInterface;
use App\Models\User;
use App\Models\Donation;
use App\Models\Campaign;
use Illuminate\Support\Collection;

class DonorDashboardService implements DonorDashboardServiceInterface
{
    public function getStatistics(User $donor): array
    {
        $totalDonations = $donor->donations()->count();
        $campaignsSupported = $donor->donations()
            ->distinct('campaign_id')
            ->count('campaign_id');
            
        $totalAmount = $donor->donations()
            ->where('status', 'completed')
            ->sum('amount');

        return [
            'total_donations' => $totalDonations,
            'campaigns_supported' => $campaignsSupported,
            'total_amount' => $totalAmount,
        ];
    }

    public function getRecentDonations(User $donor, int $limit = 5): Collection
    {
        return $donor->donations()
            ->with(['campaign.school', 'campaign.fundingRequest.studentProfile.user'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRecentActivities(User $donor, int $limit = 10): Collection
    {
        // Donation activities
        $donationActivities = $donor->donations()
            ->with('campaign')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($donation) {
                return [
                    'id' => $donation->id,
                    'type' => 'donation',
                    'message' => "You donated {$donation->amount} {$donation->currency} to '{$donation->campaign->title}'",
                    'timestamp' => $donation->created_at,
                    'status' => $donation->status,
                ];
            });

        return $donationActivities->take($limit);
    }

    public function getRecommendedCampaigns(int $limit = 5): Collection
    {
        return Campaign::where('status', 'active')
            ->where('visibility', 'public')
            ->with(['school', 'fundingRequest.studentProfile.user'])
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function getTotalDonationAmount(User $donor): float
    {
        return $donor->donations()
            ->where('status', 'completed')
            ->sum('amount');
    }
}