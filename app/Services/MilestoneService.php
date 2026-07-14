<?php

namespace App\Services;

use App\Contracts\Services\MilestoneServiceInterface;
use App\Models\Milestone;
use App\Models\StudentProfile;
use Illuminate\Support\Collection;

class MilestoneService implements MilestoneServiceInterface
{
    public function getAllByStudent($student): Collection
    {
        if ($student instanceof StudentProfile) {
            return Milestone::whereHas('fundingRequest', function ($query) use ($student) {
                $query->where('student_profile_id', $student->id);
            })->get();
        }

        return collect();
    }

    public function submitForVerification(Milestone $milestone): void
    {
        $milestone->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
    }
}