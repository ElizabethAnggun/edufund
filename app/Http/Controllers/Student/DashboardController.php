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

    public function profile(): View
    {
        $student = auth()->user()->studentProfile;
        return view('student.profile', compact('student'));
    }

    public function achievements(): View
    {
        $student = auth()->user()->studentProfile;
        $achievements = $student ? $student->achievements : collect();
        return view('student.achievements', compact('achievements'));
    }

    public function notifications(): View
    {
        $user = auth()->user();
        $notifications = $user->notifications()->paginate(20);
        return view('student.notifications', compact('notifications'));
    }

    public function settings(): View
    {
        $settings = [
            'email_notifications' => true,
            'milestone_reminders' => true,
            'newsletter' => false,
            'profile_public' => true,
        ];
        return view('student.settings', compact('settings'));
    }
}
