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
        return $user->studentProfile !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FundingRequest $fundingRequest): bool
    {
        return $fundingRequest->studentProfile->user_id === $user->id;
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
