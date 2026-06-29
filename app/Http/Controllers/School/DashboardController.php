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
        $fundingRequests = $school ? $school->fundingRequests()->with('studentProfile.user')->latest()->get() : collect();
        
        $pendingCount = $fundingRequests->where('status', FundingRequestStatus::PENDING_SCHOOL_APPROVAL)->count();
        $approvedCount = $fundingRequests->where('status', FundingRequestStatus::APPROVED)->count();
        $rejectedCount = $fundingRequests->where('status', FundingRequestStatus::REJECTED)->count();

        return view('school.dashboard', compact('fundingRequests', 'pendingCount', 'approvedCount', 'rejectedCount', 'school'));
    }
}
