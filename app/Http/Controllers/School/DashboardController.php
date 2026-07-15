<?php

namespace App\Http\Controllers\School;

use App\Enums\FundingRequestStatus;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $school = auth()->user()->school;
        $recentFundingRequests = $school ? $school->fundingRequests()->with('studentProfile.user')->latest()->limit(5)->get() : collect();
        $recentActivities = collect();

        $stats = [
            'total_students' => $school ? $school->studentProfiles()->count() : 0,
            'pending_funding_requests' => $school ? $school->fundingRequests()->where('status', FundingRequestStatus::PENDING_SCHOOL_APPROVAL)->count() : 0,
            'approved_funding_requests' => $school ? $school->fundingRequests()->where('status', FundingRequestStatus::APPROVED)->count() : 0,
            'rejected_funding_requests' => $school ? $school->fundingRequests()->where('status', FundingRequestStatus::REJECTED)->count() : 0,
        ];

        return view('school.dashboard', compact('school', 'stats', 'recentFundingRequests', 'recentActivities'));
    }

    public function students(): View
    {
        $school = auth()->user()->school;
        $students = $school ? $school->studentProfiles()->latest()->get() : collect();

        return view('school.students.index', compact('school', 'students'));
    }

    public function verifications(): View
    {
        $school = auth()->user()->school;
        $verifications = $school ? $school->verifications()->latest()->get() : collect();

        return view('school.verifications.index', compact('school', 'verifications'));
    }
}
