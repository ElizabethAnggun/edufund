<?php

namespace App\Http\Controllers\School;

use App\Contracts\Services\SchoolProfileServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private readonly SchoolProfileServiceInterface $schoolProfileService
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();
        $school = $this->schoolProfileService->getByUser($user);

        return view('school.profile', compact('school'));
    }

    public function store(SchoolProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $this->schoolProfileService->updateOrCreate($user, $request->validated());

        return redirect()->route('school.profile')->with('success', 'School profile updated successfully.');
    }
}
