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
                'walletBalance' => 0,
                'notificationCount' => 0,
                'currentMilestone' => null,
            ]);
        }

        $stats = $this->dashboardService->getStatistics($student);
        $fundingProgress = $this->dashboardService->getFundingProgress($student);
        $milestoneProgress = $this->dashboardService->getMilestoneProgress($student);
        $recentFundingRequests = $this->dashboardService->getRecentFundingRequests($student, 5);
        $recentActivities = $this->dashboardService->getRecentActivities($student, 10);
        $walletBalance = $this->dashboardService->getWalletBalance($student);
        $notificationCount = $this->dashboardService->getNotificationCount($student);
        $currentMilestone = $this->dashboardService->getCurrentMilestone($student);

        return view('student.dashboard', compact(
            'student',
            'stats',
            'fundingProgress',
            'milestoneProgress',
            'recentFundingRequests',
            'recentActivities',
            'walletBalance',
            'notificationCount',
            'currentMilestone'
        ));
    }

    public function profile(): View
    {
        $student = auth()->user()->studentProfile;
        return view('student.profile', compact('student'));
    }

    public function achievements(): View
    {
        $user = auth()->user();
        $achievements = $user->achievements()->latest('issued_at')->get();
        return view('student.achievements', compact('achievements'));
    }

    public function notifications(): View
    {
        $user = auth()->user();
        $notifications = $user->notifications()->paginate(20);
        return view('student.notifications', compact('notifications'));
    }

    public function wallet(): View
    {
        $user = auth()->user();
        $wallet = [
            'address' => 'GDET7Q23J45H...', // Dummy address
            'balance' => 0, // Dummy balance
            'network' => 'Stellar',
        ];
        $transactions = collect(); // Dummy transactions
        return view('student.wallet', compact('wallet', 'transactions'));
    }

    public function transactions(): View
    {
        $transactions = collect(); // Dummy transactions
        return view('student.transactions', compact('transactions'));
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

    public function updateSettings(): \Illuminate\Http\RedirectResponse
    {
        // Dummy implementation
        return redirect()->route('student.settings')->with('success', 'Settings updated successfully!');
    }
}
