<?php

namespace App\Http\Controllers\Student;

use App\Contracts\Services\StudentProfileServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StudentProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private readonly StudentProfileServiceInterface $studentProfileService
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();
        $profile = $this->studentProfileService->getByUser($user);

        return view('student.profile', compact('profile'));
    }

    public function store(StudentProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $this->studentProfileService->updateOrCreate($user, $request->validated());

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully.');
    }
}
