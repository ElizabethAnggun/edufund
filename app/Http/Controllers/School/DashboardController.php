<?php

namespace App\Http\Controllers\School;

use App\Contracts\Services\SchoolDashboardServiceInterface;
use App\Enums\FundingRequestStatus;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly SchoolDashboardServiceInterface $dashboardService
    ) {}

    public function index(): View
    {
        $school = auth()->user()->school;
        
        if (!$school) {
            return view('school.dashboard', [
                'school' => null,
                'stats' => [],
                'recentFundingRequests' => collect(),
                'recentActivities' => collect(),
            ]);
        }

        $stats = $this->dashboardService->getStatistics($school);
        $recentFundingRequests = $this->dashboardService->getRecentFundingRequests($school, 5);
        $recentActivities = $this->dashboardService->getRecentActivities($school, 10);

        return view('school.dashboard', compact(
            'school',
            'stats',
            'recentFundingRequests',
            'recentActivities'
        ));
    }

    public function students(): View
    {
        $school = auth()->user()->school;
        $students = $school ? $this->dashboardService->getStudentList($school) : collect();
        return view('school.students.index', compact('students'));
    }

    public function verifications(): View
    {
        $school = auth()->user()->school;
        $verifications = $school ? $this->dashboardService->getVerificationHistory($school) : collect();
        return view('school.verifications.index', compact('verifications'));
    }
}
