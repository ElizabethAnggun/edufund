<?php

namespace App\Contracts\Services;

use App\Models\School;
use Illuminate\Support\Collection;

interface SchoolDashboardServiceInterface
{
    public function getStatistics(School $school): array;
    public function getRecentFundingRequests(School $school, int $limit = 5): Collection;
    public function getRecentActivities(School $school, int $limit = 10): Collection;
    public function getStudentList(School $school, int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator;
    public function getVerificationHistory(School $school, int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator;
}