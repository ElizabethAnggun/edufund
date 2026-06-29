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

        if (isset($data['nisn'])) {
            $profileData['nisn'] = $data['nisn'];
        }
        if (isset($data['education_level'])) {
            $profileData['education_level'] = $data['education_level'];
        }
        if (isset($data['major'])) {
            $profileData['major'] = $data['major'];
        }
        if (isset($data['bio'])) {
            $profileData['bio'] = $data['bio'];
        }
        if (isset($data['phone'])) {
            $profileData['phone'] = $data['phone'];
        }
        if (isset($data['address'])) {
            $profileData['address'] = $data['address'];
        }
        if (isset($data['date_of_birth'])) {
            $profileData['date_of_birth'] = $data['date_of_birth'];
        }
        if (isset($data['gpa'])) {
            $profileData['gpa'] = $data['gpa'];
        }
        if (isset($data['semester'])) {
            $profileData['semester'] = $data['semester'];
        }
        if (isset($data['nim'])) {
            $profileData['nim'] = $data['nim'];
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
