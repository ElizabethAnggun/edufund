<?php

namespace App\Policies;

use App\Models\Milestone;
use App\Models\User;

/**
 * Authorisation rules for milestones.
 *
 * A milestone belongs to a funding request, which in turn belongs to a student
 * and a school. Students may view/submit their own milestones; the owning school
 * may view milestones linked to its funding requests.
 */
class MilestonePolicy
{
    /**
     * Determine whether the user can view the milestone.
     */
    public function view(User $user, Milestone $milestone): bool
    {
        $fundingRequest = $milestone->fundingRequest;

        if ($user->studentProfile && $fundingRequest->student_profile_id === $user->studentProfile->id) {
            return true;
        }

        if ($user->school && $fundingRequest->school_id === $user->school->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update (submit for verification) the milestone.
     */
    public function update(User $user, Milestone $milestone): bool
    {
        $fundingRequest = $milestone->fundingRequest;

        return $user->studentProfile && $fundingRequest->student_profile_id === $user->studentProfile->id;
    }
}
