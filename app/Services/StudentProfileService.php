<?php

namespace App\Services;

use App\Contracts\Services\StudentProfileServiceInterface;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class StudentProfileService implements StudentProfileServiceInterface
{
    public function getByUser(User $user): ?StudentProfile
    {
        return $user->studentProfile;
    }

    public function updateOrCreate(User $user, array $data): StudentProfile
    {
        $profileData = [
            'user_id' => $user->id,
        ];

        if (isset($data['profile_photo']) && $data['profile_photo'] instanceof \Illuminate\Http\UploadedFile) {
            if ($user->studentProfile && $user->studentProfile->profile_photo) {
                Storage::disk('public')->delete($user->studentProfile->profile_photo);
            }
            $profileData['profile_photo'] = $data['profile_photo']->store('profile_photos', 'public');
        }

        // Shared fields
        $sharedFields = [
            'education_level', 'phone', 'address', 'date_of_birth', 'bio', 'parent_name', 'parent_income'
        ];

        // High school specific fields
        $highSchoolFields = [
            'nisn', 'school_name', 'school_npsn', 'school_address', 'major', 'class', 
            'graduation_year', 'student_status', 'academic_rank'
        ];

        // University specific fields
        $universityFields = [
            'nim', 'university_name', 'university_address', 'faculty', 'study_program', 
            'semester', 'gpa', 'expected_graduation', 'scholarship_status'
        ];

        // Process all fields
        foreach (array_merge($sharedFields, $highSchoolFields, $universityFields) as $field) {
            if (isset($data[$field])) {
                $profileData[$field] = $data[$field];
            }
        }

        if ($user->studentProfile && $user->studentProfile->school_id) {
            $profileData['school_id'] = $user->studentProfile->school_id;
        } else {
            $profileData['school_id'] = 1; // Default school
        }

        return StudentProfile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );
    }
}
