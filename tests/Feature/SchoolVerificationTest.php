<?php

namespace Tests\Feature;

use App\Enums\SchoolVerificationStatus;
use App\Enums\UserRole;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SchoolVerificationTest extends FlowTestCase
{
    private function createAdmin(): User
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);
        $user->assignRole(UserRole::ADMIN->value);

        return $user;
    }

    private function createPendingSchool(): School
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);
        $user->assignRole(UserRole::SCHOOL->value);

        return School::create([
            'user_id' => $user->id,
            'name' => 'Pending School',
            'verification_status' => SchoolVerificationStatus::PENDING->value,
        ]);
    }

    public function test_admin_can_verify_school(): void
    {
        $admin = $this->createAdmin();
        $school = $this->createPendingSchool();

        $this->actingAs($admin)
            ->post(route('admin.schools.verify', $school))
            ->assertRedirect();

        $this->assertDatabaseHas('schools', [
            'id' => $school->id,
            'verification_status' => SchoolVerificationStatus::VERIFIED->value,
        ]);
    }

    public function test_admin_can_reject_school(): void
    {
        $admin = $this->createAdmin();
        $school = $this->createPendingSchool();

        $this->actingAs($admin)
            ->post(route('admin.schools.reject', $school), [
                'reason' => 'Dokumen akreditasi tidak valid',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('schools', [
            'id' => $school->id,
            'verification_status' => SchoolVerificationStatus::REJECTED->value,
        ]);
    }

    public function test_non_admin_cannot_verify_school(): void
    {
        $schoolUser = User::factory()->create(['password' => Hash::make('password')]);
        $schoolUser->assignRole(UserRole::SCHOOL->value);
        $school = School::create([
            'user_id' => $schoolUser->id,
            'name' => 'Another School',
            'verification_status' => SchoolVerificationStatus::PENDING->value,
        ]);

        $this->actingAs($schoolUser)
            ->post(route('admin.schools.verify', $school))
            ->assertForbidden();
    }
}
