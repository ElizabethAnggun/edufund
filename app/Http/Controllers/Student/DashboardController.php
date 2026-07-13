<?php

namespace App\Http\Controllers\Student;

use App\Contracts\Services\StudentDashboardServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly StudentDashboardServiceInterface $dashboardService
    ) {}

    public function index(): View
    {
        $student = auth()->user()->studentProfile;

        if (!$student) {
            return view('student.dashboard', [
                'student' => null,
                'stats' => [],
                'fundingProgress' => [],
                'milestoneProgress' => [],
                'recentFundingRequests' => collect(),
                'recentActivities' => collect(),
            ]);
        }

        $stats = $this->dashboardService->getStatistics($student);
        $fundingProgress = $this->dashboardService->getFundingProgress($student);
        $milestoneProgress = $this->dashboardService->getMilestoneProgress($student);
        $recentFundingRequests = $this->dashboardService->getRecentFundingRequests($student, 5);
        $recentActivities = $this->dashboardService->getRecentActivities($student, 10);

        return view('student.dashboard', compact(
            'student',
            'stats',
            'fundingProgress',
            'milestoneProgress',
            'recentFundingRequests',
            'recentActivities'
        ));
    }
}