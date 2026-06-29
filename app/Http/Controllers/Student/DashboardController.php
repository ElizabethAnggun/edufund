<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $profile = $user->studentProfile;
        $profileComplete = $profile && $profile->nisn && $profile->phone && $profile->address;
        $fundingRequests = $profile ? $profile->fundingRequests : collect();
        $draftCount = $fundingRequests->where('status', \App\Enums\FundingRequestStatus::DRAFT)->count();
        $submittedCount = $fundingRequests->where('status', \App\Enums\FundingRequestStatus::PENDING_SCHOOL_APPROVAL)->count();

        return view('student.dashboard', compact('profile', 'profileComplete', 'fundingRequests', 'draftCount', 'submittedCount'));
    }
}
