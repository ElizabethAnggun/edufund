<?php

namespace App\Services;

use App\Contracts\Services\StudentDashboardServiceInterface;
use App\Enums\FundingRequestStatus;
use App\Enums\MilestoneSubmissionStatus;
use App\Models\StudentProfile;
use Illuminate\Support\Collection;

class StudentDashboardService implements StudentDashboardServiceInterface
{
    public function getStatistics(StudentProfile $student): array
    {
        $fundingRequests = $student->fundingRequests();
        $milestoneSubmissions = $student->milestoneSubmissions();

        return [
            'total_funding_requests' => $fundingRequests->count(),
            'approved_funding_requests' => (clone $fundingRequests)
                ->where('status', FundingRequestStatus::APPROVED)
                ->count(),
            'total_milestones' => $student->fundingRequests()
                ->withCount('milestones')
                ->get()
                ->sum('milestones_count'),
            'completed_milestones' => $milestoneSubmissions
                ->where('status', MilestoneSubmissionStatus::VERIFIED)
                ->count(),
            'profile_completion' => $this->calculateProfileCompletion($student),
        ];
    }

    public function getFundingProgress(StudentProfile $student): array
    {
        $fundingRequests = $student->fundingRequests()
            ->with('campaign')
            ->get();

        $totalGoal = $fundingRequests->sum(
            fn ($request) => $request->campaign?->goal_amount ?? 0
        );

        $totalRaised = $fundingRequests->sum(
            fn ($request) => $request->campaign?->current_amount ?? 0
        );

        return [
            'total_goal' => $totalGoal,
            'total_raised' => $totalRaised,
            'progress_percentage' => $totalGoal > 0 ? round(($totalRaised / $totalGoal) * 100, 2) : 0,
            'active_campaigns' => $fundingRequests->filter(
                fn ($request) => $request->campaign !== null
            )->count(),
        ];
    }

    public function getMilestoneProgress(StudentProfile $student): array
    {
        $totalMilestones = $student->fundingRequests()
            ->withCount('milestones')
            ->get()
            ->sum('milestones_count');

        $completedMilestones = $student->milestoneSubmissions()
            ->where('status', MilestoneSubmissionStatus::VERIFIED)
            ->count();

        $pendingMilestones = $student->milestoneSubmissions()
            ->where('status', MilestoneSubmissionStatus::PENDING)
            ->count();

        return [
            'total_milestones' => $totalMilestones,
            'completed_milestones' => $completedMilestones,
            'pending_milestones' => $pendingMilestones,
            'progress_percentage' => $totalMilestones > 0 ? round(($completedMilestones / $totalMilestones) * 100, 2) : 0,
        ];
    }

    public function getRecentFundingRequests(StudentProfile $student, int $limit = 5): Collection
    {
        return $student->fundingRequests()
            ->with(['school', 'campaign'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRecentActivities(StudentProfile $student, int $limit = 10): Collection
    {
        $fundingActivities = $student->fundingRequests()
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($request) {
                return [
                    'id' => 'funding-request-'.$request->getKey(),
                    'message' => "Funding request '{$request->title}' is {$request->status->value}.",
                    'timestamp' => $request->submitted_at ?? $request->updated_at ?? $request->created_at,
                ];
            });

        $milestoneActivities = $student->milestoneSubmissions()
            ->with('milestone')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($submission) {
                $milestoneTitle = $submission->milestone?->title ?? 'Milestone';

                return [
                    'id' => 'milestone-'.$submission->getKey(),
                    'message' => "{$milestoneTitle} is {$submission->status->value}.",
                    'timestamp' => $submission->submitted_at ?? $submission->updated_at ?? $submission->created_at,
                ];
            });

        $achievementActivities = $student->user
            ?->achievements()
            ->latest('issued_at')
            ->limit($limit)
            ->get()
            ->map(function ($achievement) {
                return [
                    'id' => 'achievement-'.$achievement->getKey(),
                    'message' => "Achievement '{$achievement->title}' was issued.",
                    'timestamp' => $achievement->issued_at ?? $achievement->created_at,
                ];
            }) ?? collect();

        return $fundingActivities
            ->merge($milestoneActivities)
            ->merge($achievementActivities)
            ->sortByDesc('timestamp')
            ->take($limit)
            ->values();
    }

    private function calculateProfileCompletion(StudentProfile $student): int
    {
        $fields = [
            'profile_photo',
            'nisn',
            'nim',
            'education_level',
            'major',
            'semester',
            'gpa',
            'date_of_birth',
            'phone',
            'address',
            'bio',
            'student_id_card',
        ];

        $filledFields = 0;

        foreach ($fields as $field) {
            if (!empty($student->{$field})) {
                $filledFields++;
            }
        }

        return (int) round(($filledFields / count($fields)) * 100);
    }
}
