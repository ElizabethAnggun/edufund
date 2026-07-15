<?php

namespace App\Contracts\Services;

use App\Models\User;
use Illuminate\Support\Collection;

interface AdminDashboardServiceInterface
{
    public function getStatistics(): array;
    public function getRecentUsers(int $limit = 5): Collection;
    public function getRecentActivities(int $limit = 10): Collection;
}
