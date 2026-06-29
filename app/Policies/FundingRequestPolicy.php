<?php

namespace App\Policies;

use App\Enums\FundingRequestStatus;
use App\Models\FundingRequest;
use App\Models\User;

class FundingRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->studentProfile !== null || $user->school !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FundingRequest $fundingRequest): bool
    {
        if ($user->studentProfile) {
            return $fundingRequest->studentProfile->user_id === $user->id;
        }
        if ($user->school) {
            return $fundingRequest->school_id === $user->school->id;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->studentProfile !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FundingRequest $fundingRequest): bool
    {
        return $fundingRequest->studentProfile->user_id === $user->id && $fundingRequest->status === FundingRequestStatus::DRAFT;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FundingRequest $fundingRequest): bool
    {
        return $fundingRequest->studentProfile->user_id === $user->id && $fundingRequest->status === FundingRequestStatus::DRAFT;
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, FundingRequest $fundingRequest): bool
    {
        if (!$user->school) {
            return false;
        }
        
        if ($fundingRequest->school_id !== $user->school->id) {
            return false;
        }
        
        return in_array($fundingRequest->status, [
            FundingRequestStatus::DRAFT,
            FundingRequestStatus::PENDING_SCHOOL_APPROVAL,
        ]);
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, FundingRequest $fundingRequest): bool
    {
        return $this->approve($user, $fundingRequest);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FundingRequest $fundingRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FundingRequest $fundingRequest): bool
    {
        return false;
    }
}
