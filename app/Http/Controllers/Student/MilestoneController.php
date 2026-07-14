<?php

namespace App\Http\Controllers\Student;

use App\Contracts\Services\MilestoneServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MilestoneController extends Controller
{
    public function __construct(
        private readonly MilestoneServiceInterface $milestoneService
    ) {
    }

    public function index(): View
    {
        $student = auth()->user()->studentProfile;
        $milestones = $this->milestoneService->getAllByStudent($student);

        return view('student.milestones.index', compact('milestones'));
    }

    public function show(\App\Models\Milestone $milestone): View
    {
        $this->authorize('view', $milestone);

        return view('student.milestones.show', compact('milestone'));
    }

    public function submit(Request $request, \App\Models\Milestone $milestone): RedirectResponse
    {
        $this->authorize('update', $milestone);
        $this->milestoneService->submitForVerification($milestone);

        return redirect()->route('student.milestones.show', $milestone)
            ->with('success', 'Milestone submitted for verification successfully.');
    }
}