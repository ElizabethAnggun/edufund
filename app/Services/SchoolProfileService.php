<?php

namespace App\Services;

use App\Contracts\Services\SchoolProfileServiceInterface;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class SchoolProfileService implements SchoolProfileServiceInterface
{
    public function getByUser(User $user): ?School
    {
        return $user->school;
    }

    public function updateOrCreate(User $user, array $data): School
    {
        $profileData = [
            'user_id' => $user->id,
        ];

        // Handle logo upload
        if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
            if ($user->school && $user->school->logo) {
                Storage::disk('public')->delete($user->school->logo);
            }
            $profileData['logo'] = $data['logo']->store('school_logos', 'public');
        }

        // Handle accreditation document upload
        if (isset($data['accreditation_document']) && $data['accreditation_document'] instanceof \Illuminate\Http\UploadedFile) {
            if ($user->school && $user->school->accreditation_document) {
                Storage::disk('public')->delete($user->school->accreditation_document);
            }
            $profileData['accreditation_document'] = $data['accreditation_document']->store('accreditation_documents', 'public');
        }

        // Process all other fields
        $fields = [
            'name', 'npsn', 'address', 'headmaster_name', 'phone', 'email',
            'accreditation_number', 'stellar_wallet_address'
        ];

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $profileData[$field] = $data[$field];
            }
        }

        return School::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );
    }
}
