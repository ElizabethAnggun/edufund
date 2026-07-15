<?php

namespace Tests\Feature;

use App\Enums\CampaignStatus;
use App\Enums\CampaignVisibility;
use App\Enums\FundingRequestStatus;
use App\Enums\MilestoneStatus;
use App\Enums\UserRole;
use App\Models\Campaign;
use App\Models\FundingRequest;
use App\Models\Milestone;
use App\Models\School;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MilestoneFlowTest extends FlowTestCase
{
    /**
     * @return array{studentUser: User, milestone: Milestone}
     */
    private function setupMilestoneForStudent(): array
    {
        $schoolUser = User::factory()->create(['password' => Hash::make('password')]);
        $schoolUser->assignRole(UserRole::SCHOOL->value);
        $school = School::create([
            'user_id' => $schoolUser->id,
            'name' => 'Milestone School',
            'stellar_wallet_address' => 'GSCHOOLMILESTONEWALLET',
            'verification_status' => 'verified',
        ]);

        $studentUser = User::factory()->create(['password' => Hash::make('password')]);
        $studentUser->assignRole(UserRole::STUDENT->value);
        $studentProfile = StudentProfile::create([
            'user_id' => $studentUser->id,
            'school_id' => $school->id,
            'nisn' => 'NISN-MILE-001',
            'education_level' => 's1',
            'major' => 'CS',
            'semester' => 4,
            'gpa' => 3.5,
            'date_of_birth' => '2004-01-01',
            'phone' => '08123',
            'address' => 'Addr',
        ]);

        $fr = FundingRequest::create([
            'student_profile_id' => $studentProfile->id,
            'school_id' => $school->id,
            'title' => 'FR Milestone',
            'description' => 'desc',
            'total_amount' => 10000000,
            'currency' => 'XLM',
            'deadline' => now()->addMonths(2),
            'category' => 'tuition',
            'purpose' => 'purpose',
            'status' => FundingRequestStatus::APPROVED,
        ]);

        $campaign = Campaign::create([
            'funding_request_id' => $fr->id,
            'school_id' => $school->id,
            'title' => 'Campaign Milestone',
            'slug' => 'campaign-milestone',
            'description' => 'desc',
            'goal_amount' => 10000000,
            'current_amount' => 0,
            'status' => CampaignStatus::ACTIVE,
            'visibility' => CampaignVisibility::PUBLIC,
            'published_at' => now(),
        ]);

        $milestone = Milestone::create([
            'funding_request_id' => $fr->id,
            'campaign_id' => $campaign->id,
            'title' => 'Milestone Submit',
            'description' => 'desc',
            'amount' => 2500000,
            'status' => MilestoneStatus::IN_PROGRESS,
        ]);

        return ['studentUser' => $studentUser, 'milestone' => $milestone];
    }

    public function test_student_can_submit_milestone_for_verification(): void
    {
        $data = $this->setupMilestoneForStudent();

        $this->actingAs($data['studentUser'])
            ->post(route('student.milestones.submit', $data['milestone']))
            ->assertRedirect();

        $this->assertDatabaseHas('milestones', [
            'id' => $data['milestone']->id,
            'status' => MilestoneStatus::SUBMITTED->value,
        ]);
    }

    public function test_student_cannot_submit_another_students_milestone(): void
    {
        $data = $this->setupMilestoneForStudent();

        $otherStudent = User::factory()->create(['password' => Hash::make('password')]);
        $otherStudent->assignRole(UserRole::STUDENT->value);
        StudentProfile::create([
            'user_id' => $otherStudent->id,
            'school_id' => $data['milestone']->fundingRequest->school_id,
            'nisn' => 'NISN-OTHER-001',
            'education_level' => 's1',
            'major' => 'CS',
            'semester' => 4,
            'gpa' => 3.5,
            'date_of_birth' => '2004-01-01',
            'phone' => '08123',
            'address' => 'Addr',
        ]);

        $this->actingAs($otherStudent)
            ->post(route('student.milestones.submit', $data['milestone']))
            ->assertForbidden();
    }
}
