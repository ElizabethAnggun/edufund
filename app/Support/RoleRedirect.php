<?php

namespace App\Support;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class RoleRedirect
{
    public static function toDashboard(User $user): ?RedirectResponse
    {
        if ($user->hasRole(UserRole::ADMIN->value)) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole(UserRole::STUDENT->value)) {
            return redirect()->route('student.dashboard');
        }

        if ($user->hasRole(UserRole::SCHOOL->value)) {
            return redirect()->route('school.dashboard');
        }

        if ($user->hasRole(UserRole::DONOR->value)) {
            return redirect()->route('donor.dashboard');
        }

        return null;
    }
}
