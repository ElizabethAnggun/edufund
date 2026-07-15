<?php

namespace App\Services;

use App\Contracts\Services\AdminDashboardServiceInterface;
use App\Models\User;
use App\Models\School;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\BlockchainTransaction;
use Illuminate\Support\Collection;

class AdminDashboardService implements AdminDashboardServiceInterface
{
    public function getStatistics(): array
    {
        return [
            'total_users' => User::count(),
            'total_schools' => School::count(),
            'verified_schools' => School::where('verification_status', 'verified')->count(),
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'total_donations' => Donation::where('status', 'completed')->sum('amount'),
            'total_transactions' => BlockchainTransaction::count(),
            'pending_verifications' => School::where('verification_status', 'pending')->count(),
        ];
    }

    public function getRecentUsers(int $limit = 5): Collection
    {
        return User::latest()->limit($limit)->get();
    }

    public function getRecentActivities(int $limit = 10): Collection
    {
        $activities = collect();

        School::latest()->limit($limit)->get()->each(function ($school) use (&$activities) {
            $verificationStatus = $school->verification_status->value;
            $activities->push([
                'id' => "school_{$school->id}",
                'type' => 'school',
                'message' => "School '{$school->name}' registered ({$verificationStatus})",
                'timestamp' => $school->created_at,
                'status' => $verificationStatus,
            ]);
        });

        Donation::with('campaign')->latest()->limit($limit)->get()->each(function ($donation) use (&$activities) {
            $campaignTitle = $donation->campaign ? ($donation->campaign->title ?? "Campaign #{$donation->campaign_id}") : "Campaign #{$donation->campaign_id}";
            $donationStatus = $donation->status->value;
            $activities->push([
                'id' => "donation_{$donation->id}",
                'type' => 'donation',
                'message' => "Donation of {$donation->currency} {$donation->amount} made to '{$campaignTitle}'",
                'timestamp' => $donation->created_at,
                'status' => $donationStatus,
            ]);
        });

        return $activities->sortByDesc('timestamp')->take($limit)->values();
    }
}
