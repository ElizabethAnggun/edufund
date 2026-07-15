<?php

namespace App\Http\Controllers\School;

use App\Contracts\Services\SchoolDashboardServiceInterface;
use App\Enums\FundingRequestStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly SchoolDashboardServiceInterface $dashboardService
    ) {}

    public function index(): View|RedirectResponse
    {
        $school = auth()->user()->school;
        
        if (!$school) {
            return redirect()->route('school.profile')->with('warning', 'Please complete your school profile first.');
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

    public function students(): View|RedirectResponse
    {
        $school = auth()->user()->school;
        
        if (!$school) {
            return redirect()->route('school.profile')->with('warning', 'Please complete your school profile first.');
        }
        
        $students = $this->dashboardService->getStudentList($school);
        return view('school.students.index', compact('students'));
    }

    public function verifications(): View|RedirectResponse
    {
        $school = auth()->user()->school;
        
        if (!$school) {
            return redirect()->route('school.profile')->with('warning', 'Please complete your school profile first.');
        }
        
        $verifications = $this->dashboardService->getVerificationHistory($school);
        return view('school.verifications.index', compact('verifications'));
    }
}
