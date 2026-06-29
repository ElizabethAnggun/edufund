<?php

namespace App\Contracts\Services;

use App\Models\StudentProfile;
use App\Models\User;

interface StudentProfileServiceInterface
{
    public function getByUser(User $user): ?StudentProfile;
    public function updateOrCreate(User $user, array $data): StudentProfile;
}
