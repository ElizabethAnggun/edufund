<?php

use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->hasRole(UserRole::ADMIN->value)) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole(UserRole::STUDENT->value)) {
            return redirect()->route('student.dashboard');
        } elseif ($user->hasRole(UserRole::SCHOOL->value)) {
            return redirect()->route('school.dashboard');
        } elseif ($user->hasRole(UserRole::DONOR->value)) {
            return redirect()->route('donor.dashboard');
        }
    }
    return redirect()->route('login');
});
