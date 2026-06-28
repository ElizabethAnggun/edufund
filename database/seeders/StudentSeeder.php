<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\School;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = School::first();

        // Student 1
        $user1 = User::firstOrCreate(
            ['email' => 'student1@edufund.test'],
            [
                'name' => 'Andi Pratama',
                'password' => Hash::make('password'),
            ]
        );
        $user1->assignRole(UserRole::STUDENT->value);
        StudentProfile::firstOrCreate(
            ['user_id' => $user1->id],
            [
                'school_id' => $school->id,
                'nim' => '1234567890',
                'major' => 'Teknik Informatika',
                'semester' => 6,
                'gpa' => 3.85,
                'date_of_birth' => '2000-01-01',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 1, Jakarta',
            ]
        );

        // Student 2
        $user2 = User::firstOrCreate(
            ['email' => 'student2@edufund.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
            ]
        );
        $user2->assignRole(UserRole::STUDENT->value);
        StudentProfile::firstOrCreate(
            ['user_id' => $user2->id],
            [
                'school_id' => $school->id,
                'nim' => '0987654321',
                'major' => 'Ilmu Komputer',
                'semester' => 4,
                'gpa' => 3.70,
                'date_of_birth' => '2001-05-05',
                'phone' => '089876543210',
                'address' => 'Jl. Kebon Jeruk No. 2, Jakarta',
            ]
        );
    }
}
