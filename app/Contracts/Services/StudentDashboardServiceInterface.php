<?php

namespace App\Contracts\Services;

use App\Models\StudentProfile;
use Illuminate\Support\Collection;

interface StudentDashboardServiceInterface
{
    public function getStatistics(StudentProfile $student): array;
    public function getFundingProgress(StudentProfile $student): array;
    public function getRecentFundingRequests(StudentProfile $student, int $limit = 5): Collection;
    public function getRecentActivities(StudentProfile $student, int $limit = 10): Collection;
    public function getMilestoneProgress(StudentProfile $student): array;
}