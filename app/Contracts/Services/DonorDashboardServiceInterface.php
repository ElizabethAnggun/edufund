<?php

namespace App\Contracts\Services;

use App\Models\User;
use Illuminate\Support\Collection;

interface DonorDashboardServiceInterface
{
    public function getStatistics(User $donor): array;
    public function getRecentDonations(User $donor, int $limit = 5): Collection;
    public function getRecentActivities(User $donor, int $limit = 10): Collection;
    public function getRecommendedCampaigns(int $limit = 5): Collection;
    public function getTotalDonationAmount(User $donor): float;
}