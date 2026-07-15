<?php

namespace Tests\Feature;

use App\Enums\FundingCategory;
use App\Enums\FundingRequestStatus;
use App\Enums\UserRole;
use App\Models\FundingRequest;
use App\Models\School;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FundingRequestFlowTest extends FlowTestCase
{
    private function createSchool(): School
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);
        $user->assignRole(UserRole::SCHOOL->value);

        return School::create([
            'user_id' => $user->id,
            'name' => 'Test School',
            'stellar_wallet_address' => 'GTESTSCHOOLWALLETADDRESS',
            'verification_status' => 'verified',
        ]);
    }

    private function createStudent(School $school): User
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);
        $user->assignRole(UserRole::STUDENT->value);

        StudentProfile::create([
            'user_id' => $user->id,
            'school_id' => $school->id,
            'nisn' => 'NISN-TEST-001',
            'nim' => 'NIM-TEST-001',
            'education_level' => 's1',
            'major' => 'Computer Science',
            'semester' => 4,
            'gpa' => 3.75,
            'date_of_birth' => '2004-01-01',
            'phone' => '08123456789',
            'address' => 'Jl. Test No. 1',
        ]);

        return $user;
    }

    private function makeFundingRequest(User $student, School $school, string $status): FundingRequest
    {
        return FundingRequest::create([
            'student_profile_id' => $student->studentProfile->id,
            'school_id' => $school->id,
            'title' => 'Bantuan SPP',
            'description' => 'Butuh bantuan untuk membayar SPP.',
            'total_amount' => 5000000,
            'currency' => 'XLM',
            'deadline' => now()->addMonths(2),
            'category' => FundingCategory::TUITION->value,
            'purpose' => 'SPP',
            'status' => $status,
        ]);
    }

    public function test_guest_cannot_access_funding_request_pages(): void
    {
        $this->get(route('student.funding-requests.index'))->assertRedirect(route('login'));
    }

    public function test_student_can_create_funding_request(): void
    {
        $school = $this->createSchool();
        $student = $this->createStudent($school);

        $response = $this->actingAs($student)->post(route('student.funding-requests.store'), [
            'title' => 'Bantuan SPP',
            'description' => 'Butuh bantuan untuk membayar SPP.',
            'total_amount' => 5000000,
            'deadline' => now()->addMonths(2)->format('Y-m-d'),
            'category' => FundingCategory::TUITION->value,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('funding_requests', [
            'title' => 'Bantuan SPP',
            'status' => FundingRequestStatus::DRAFT->value,
        ]);
    }

    public function test_student_can_submit_funding_request(): void
    {
        $school = $this->createSchool();
        $student = $this->createStudent($school);
        $fr = $this->makeFundingRequest($student, $school, FundingRequestStatus::DRAFT->value);

        $this->actingAs($student)
            ->post(route('student.funding-requests.submit', $fr))
            ->assertRedirect();

        $this->assertDatabaseHas('funding_requests', [
            'id' => $fr->id,
            'status' => FundingRequestStatus::PENDING_SCHOOL_APPROVAL->value,
        ]);
    }

    public function test_school_can_approve_funding_request_and_campaign_is_created(): void
    {
        $school = $this->createSchool();
        $student = $this->createStudent($school);
        $fr = $this->makeFundingRequest($student, $school, FundingRequestStatus::PENDING_SCHOOL_APPROVAL->value);

        $this->actingAs($school->user)
            ->post(route('school.funding-requests.approve', $fr))
            ->assertRedirect();

        $this->assertDatabaseHas('funding_requests', [
            'id' => $fr->id,
            'status' => FundingRequestStatus::APPROVED->value,
        ]);
        $this->assertDatabaseHas('campaigns', [
            'funding_request_id' => $fr->id,
        ]);
    }

    public function test_school_can_reject_funding_request(): void
    {
        $school = $this->createSchool();
        $student = $this->createStudent($school);
        $fr = $this->makeFundingRequest($student, $school, FundingRequestStatus::PENDING_SCHOOL_APPROVAL->value);

        $this->actingAs($school->user)
            ->post(route('school.funding-requests.reject', $fr), [
                'rejection_reason' => 'Dokumen pendukung tidak lengkap',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('funding_requests', [
            'id' => $fr->id,
            'status' => FundingRequestStatus::REJECTED->value,
        ]);
    }

    public function test_student_cannot_view_another_students_funding_request(): void
    {
        $school = $this->createSchool();
        $studentA = $this->createStudent($school);
        $studentB = $this->createStudent($school);
        $fr = $this->makeFundingRequest($studentA, $school, FundingRequestStatus::DRAFT->value);

        $this->actingAs($studentB)
            ->get(route('student.funding-requests.show', $fr))
            ->assertForbidden();
    }
}
