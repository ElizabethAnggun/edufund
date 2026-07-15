<?php

namespace Tests\Feature;

use App\Enums\CampaignStatus;
use App\Enums\CampaignVisibility;
use App\Enums\DisbursementStatus;
use App\Enums\FundingRequestStatus;
use App\Enums\MilestoneStatus;
use App\Enums\UserRole;
use App\Models\Campaign;
use App\Models\Disbursement;
use App\Models\FundingRequest;
use App\Models\Milestone;
use App\Models\School;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DisbursementFlowTest extends FlowTestCase
{
    /**
     * Build a school, its student, an approved funding request, a campaign and a
     * disbursable milestone, returning the key models.
     *
     * @return array{school: School, schoolUser: User, fr: FundingRequest, milestone: Milestone}
     */
    private function setupDisbursableMilestone(): array
    {
        $schoolUser = User::factory()->create(['password' => Hash::make('password')]);
        $schoolUser->assignRole(UserRole::SCHOOL->value);
        $school = School::create([
            'user_id' => $schoolUser->id,
            'name' => 'Disburse School',
            'stellar_wallet_address' => 'GSCHOOLDISBURSEWALLET',
            'verification_status' => 'verified',
        ]);

        $studentUser = User::factory()->create([
            'password' => Hash::make('password'),
            'wallet_address' => 'GSTUDENTDISBURSEWALLET',
        ]);
        $studentUser->assignRole(UserRole::STUDENT->value);
        $studentProfile = StudentProfile::create([
            'user_id' => $studentUser->id,
            'school_id' => $school->id,
            'nisn' => 'NISN-DISB-001',
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
            'title' => 'FR Disburse',
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
            'title' => 'Campaign Disburse',
            'slug' => 'campaign-disburse',
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
            'title' => 'Milestone 1',
            'description' => 'desc',
            'amount' => 2500000,
            'status' => MilestoneStatus::SUBMITTED,
        ]);

        return ['school' => $school, 'schoolUser' => $schoolUser, 'fr' => $fr, 'milestone' => $milestone];
    }

    public function test_school_can_release_milestone_funds(): void
    {
        $data = $this->setupDisbursableMilestone();

        $this->actingAs($data['schoolUser'])
            ->post(route('school.disbursements.store', [$data['fr'], $data['milestone']]), [
                'notes' => 'Cairkan dana.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('disbursements', [
            'milestone_id' => $data['milestone']->id,
            'status' => DisbursementStatus::COMPLETED->value,
        ]);
        $this->assertDatabaseHas('milestones', [
            'id' => $data['milestone']->id,
            'status' => MilestoneStatus::COMPLETED->value,
        ]);
    }

    public function test_school_cannot_release_already_disbursed_milestone(): void
    {
        $data = $this->setupDisbursableMilestone();

        $this->actingAs($data['schoolUser'])
            ->post(route('school.disbursements.store', [$data['fr'], $data['milestone']]), ['notes' => 'first']);

        $this->actingAs($data['schoolUser'])
            ->post(route('school.disbursements.store', [$data['fr'], $data['milestone']]), ['notes' => 'second'])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertEquals(1, Disbursement::where('milestone_id', $data['milestone']->id)->count());
    }

    public function test_other_school_cannot_disburse_milestone(): void
    {
        $data = $this->setupDisbursableMilestone();

        $otherSchoolUser = User::factory()->create(['password' => Hash::make('password')]);
        $otherSchoolUser->assignRole(UserRole::SCHOOL->value);
        School::create([
            'user_id' => $otherSchoolUser->id,
            'name' => 'Other School',
            'stellar_wallet_address' => 'GOTHERSCHOOLWALLET',
            'verification_status' => 'verified',
        ]);

        $this->actingAs($otherSchoolUser)
            ->post(route('school.disbursements.store', [$data['fr'], $data['milestone']]), ['notes' => 'hack'])
            ->assertForbidden();
    }
}
