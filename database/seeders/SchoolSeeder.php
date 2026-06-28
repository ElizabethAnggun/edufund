<?php

namespace Database\Seeders;

use App\Enums\SchoolVerificationStatus;
use App\Enums\UserRole;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verified school
        $user1 = User::firstOrCreate(
            ['email' => 'school1@edufund.test'],
            [
                'name' => 'SMAN 1 Jakarta',
                'password' => Hash::make('password'),
            ]
        );
        $user1->assignRole(UserRole::SCHOOL->value);
        School::firstOrCreate(
            ['user_id' => $user1->id],
            [
                'name' => 'SMAN 1 Jakarta',
                'address' => 'Jl. Sudirman No. 1, Jakarta',
                'phone' => '021-1234567',
                'email' => 'school1@edufund.test',
                'accreditation_number' => 'AKR-001-2024',
                'verification_status' => SchoolVerificationStatus::VERIFIED->value,
                'verified_at' => now(),
            ]
        );

        // Pending school
        $user2 = User::firstOrCreate(
            ['email' => 'school2@edufund.test'],
            [
                'name' => 'SMAN 2 Bandung',
                'password' => Hash::make('password'),
            ]
        );
        $user2->assignRole(UserRole::SCHOOL->value);
        School::firstOrCreate(
            ['user_id' => $user2->id],
            [
                'name' => 'SMAN 2 Bandung',
                'address' => 'Jl. Asia Afrika No. 100, Bandung',
                'phone' => '022-7654321',
                'email' => 'school2@edufund.test',
                'accreditation_number' => 'AKR-002-2024',
                'verification_status' => SchoolVerificationStatus::PENDING->value,
            ]
        );
    }
}
