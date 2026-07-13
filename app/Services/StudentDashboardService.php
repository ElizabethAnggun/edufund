<?php

namespace App\Services;

use App\Contracts\Services\StudentDashboardServiceInterface;
use App\Models\StudentProfile;
use App\Models\FundingRequest;
use App\Models\MilestoneSubmission;
use App\Models\Achievement;
use Illuminate\Support\Collection;

class StudentDashboardService implements StudentDashboardServiceInterface
{
    public function getStatistics(StudentProfile $student): array
    {
        $totalFundingRequests = $student->fundingRequests()->count();
        $approvedFundingRequests = $student->fundingRequests()->where('status', 'approved')->count();
        $totalMilestones = $student->milestoneSubmissions()->count();
        $completedMilestones = $student->milestoneSubmissions()->where('status', 'approved')->count();

        return [
            'total_funding_requests' => $totalFundingRequests,
            'approved_funding_requests' => $approvedFundingRequests,
            'total_milestones' => $totalMilestones,
            'completed_milestones' => $completedMilestones,
            'profile_completion' => $this->calculateProfileCompletion($student),
        ];
    }

    public function getFundingProgress(StudentProfile $student): array
    {
        $fundingRequests = $student->fundingRequests()
            ->with('campaign')
            ->get();

        $totalGoal = $fundingRequests->sum(function ($request) {
            return $request->campaign ? $request->campaign->goal_amount : 0;
        });

        $totalRaised = $fundingRequests->sum(function ($request) {
            return $request->campaign ? $request->campaign->current_amount : 0;
        });

        $progressPercentage = $totalGoal > 0 ? round(($totalRaised / $totalGoal) * 100, 2) : 0;

        return [
            'total_goal' => $totalGoal,
            'total_raised' => $totalRaised,
            'progress_percentage' => $progressPercentage,
            'active_campaigns' => $fundingRequests->where('campaign.status', 'active')->count(),
        ];
    }

    public function getRecentFundingRequests(StudentProfile $student, int $limit = 5): Collection
    {
        return $student->fundingRequests()
            ->with('campaign', 'school')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRecentActivities(StudentProfile $student, int $limit = 10): Collection
    {
        // Funding request activities
        $fundingActivities = $student->fundingRequests()
            ->latest('submitted_at')
            ->limit($limit)
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'type' => 'funding_request',
                    'message' => "Funding request '{$request->title}' was {$request->status->value}",
                    'timestamp' => $request->submitted_at,
                    'status' => $request->status->value,
                ];
            });

        // Milestone activities
        $milestoneActivities = $student->milestoneSubmissions()
            ->with('milestone')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($submission) {
                return [
                    'id' => $submission->id,
                    'type' => 'milestone',
                    'message' => "Milestone '{$submission->milestone->title}' was {$submission->status->value}",
                    'timestamp' => $submission->submitted_at,
                    'status' => $submission->status->value,
                ];
            });

        // Achievement activities
        $achievementActivities = $student->achievements()
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($achievement) {
                return [
                    'id' => $achievement->id,
                    'type' => 'achievement',
                    'message' => "You earned the '{$achievement->title}' badge",
                    'timestamp' => $achievement->created_at,
                    'status' => 'completed',
                ];
            });

        // Merge and sort by timestamp
        $activities = $fundingActivities
            ->merge($milestoneActivities)
            ->merge($achievementActivities)
            ->sortByDesc('timestamp')
            ->take($limit)
            ->values();

        return $activities;
    }

    public function getMilestoneProgress(StudentProfile $student): array
    {
        $totalMilestones = $student->fundingRequests()
            ->whereHas('campaign', function ($query) {
                $query->where('status', 'active');
            })
            ->with('milestones')
            ->get()
            ->sum(function ($request) {
                return $request->milestones->count();
            });

        $completedMilestones = $student->milestoneSubmissions()
            ->where('status', 'approved')
            ->count();

        $pendingMilestones = $student->milestoneSubmissions()
            ->where('status', 'pending')
            ->count();

        $progressPercentage = $totalMilestones > 0 ? round(($completedMilestones / $totalMilestones) * 100, 2) : 0;

        return [
            'total_milestones' => $totalMilestones,
            'completed_milestones' => $completedMilestones,
            'pending_milestones' => $pendingMilestones,
            'progress_percentage' => $progressPercentage,
        ];
    }

    private function calculateProfileCompletion(StudentProfile $student): int
    {
        $fields = [
            'profile_photo', 'nisn', 'nim', 'education_level', 'major', 
            'semester', 'gpa', 'date_of_birth', 'phone', 'address', 'bio', 'student_id_card'
        ];

        $filledFields = 0;
        foreach ($fields as $field) {
            if (!empty($student->$field)) {
                $filledFields++;
            }
        }

        return round(($filledFields / count($fields)) * 100);
    }
}