<?php

namespace Database\Seeders;

use App\Enums\EducationLevel;
use App\Enums\FundingCategory;
use App\Enums\FundingRequestStatus;
use App\Models\FundingRequest;
use App\Models\School;
use App\Models\StudentProfile;
use Illuminate\Database\Seeder;

class FundingRequestSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        $students = StudentProfile::all();

        foreach ($students as $index => $student) {
            FundingRequest::firstOrCreate(
                ['student_profile_id' => $student->id, 'title' => 'Bantuan Biaya Pendidikan ' . ($index + 1)],
                [
                    'school_id' => $school->id,
                    'description' => 'Bantuan biaya pendidikan untuk semester ' . $student->semester . ' di jurusan ' . $student->major . '.',
                    'purpose' => 'Biaya kuliah dan buku.',
                    'total_amount' => rand(5000000, 15000000),
                    'education_level' => $index === 0 ? EducationLevel::S1 : EducationLevel::SMA,
                    'category' => $index === 0 ? FundingCategory::TUITION : FundingCategory::BOOKS,
                    'status' => $index === 0 ? FundingRequestStatus::PENDING_SCHOOL_APPROVAL : FundingRequestStatus::APPROVED,
                    'school_approved_at' => $index === 1 ? now() : null,
                    'deadline' => now()->addMonths(6),
                ]
            );
        }
    }
}
