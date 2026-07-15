<?php

namespace App\Contracts\Services;

use App\Models\School;
use App\Models\User;

interface SchoolProfileServiceInterface
{
    public function getByUser(User $user): ?School;
    public function updateOrCreate(User $user, array $data): School;
}
