<?php

namespace App\Services;

use App\Contracts\Services\SchoolDashboardServiceInterface;
use App\Models\School;
use App\Models\FundingRequest;
use App\Models\StudentProfile;
use App\Models\Verification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SchoolDashboardService implements SchoolDashboardServiceInterface
{
    public function getStatistics(School $school): array
    {
        $totalStudents = $school->studentProfiles()->count();

        $pendingFundingRequests = $school->fundingRequests()
            ->where('status', 'pending_school_approval')
            ->count();

        $approvedFundingRequests = $school->fundingRequests()
            ->whereIn('status', ['approved', 'active', 'completed'])
            ->count();

        $rejectedFundingRequests = $school->fundingRequests()
            ->where('status', 'rejected')
            ->count();

        $totalRaised = (float) $school->campaigns()->sum('current_amount');

        $totalDisbursed = (float) $school->disbursements()
            ->where('status', 'completed')
            ->sum('amount');

        return [
            'total_students' => $totalStudents,
            'pending_funding_requests' => $pendingFundingRequests,
            'approved_funding_requests' => $approvedFundingRequests,
            'rejected_funding_requests' => $rejectedFundingRequests,
            'total_raised' => $totalRaised,
            'total_disbursed' => $totalDisbursed,
        ];
    }

    public function getRecentFundingRequests(School $school, int $limit = 5): Collection
    {
        return $school->fundingRequests()
            ->with(['studentProfile.user', 'campaign'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRecentActivities(School $school, int $limit = 10): Collection
    {
        // Get recent funding request activities
        $fundingActivities = $school->fundingRequests()
            ->with('studentProfile.user')
            ->latest('submitted_at')
            ->limit($limit)
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'type' => 'funding_request',
                    'message' => "Funding request '{$request->title}' was submitted by {$request->studentProfile->user->name}",
                    'timestamp' => $request->submitted_at,
                    'status' => $request->status->value,
                ];
            });

        // Get recent verifications
        $verificationActivities = Verification::where('verifiable_type', School::class)
            ->where('verifiable_id', $school->id)
            ->with('verifier')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($verification) {
                return [
                    'id' => $verification->id,
                    'type' => 'verification',
                    'message' => "Verification {$verification->status->value} by {$verification->verifier->name}",
                    'timestamp' => $verification->created_at,
                    'status' => $verification->status->value,
                ];
            });

        // Merge and sort by timestamp
        $activities = $fundingActivities->merge($verificationActivities)
            ->sortByDesc('timestamp')
            ->take($limit)
            ->values();

        return $activities;
    }

    public function getStudentList(School $school, int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $school->studentProfiles()
            ->with(['user', 'fundingRequests'])
            ->latest()
            ->paginate($perPage);
    }

    public function getVerificationHistory(School $school, int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Verification::where('verifiable_type', School::class)
            ->where('verifiable_id', $school->id)
            ->with('verifier')
            ->latest()
            ->paginate($perPage);
    }
}