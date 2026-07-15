<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SchoolVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->get('tab', 'pending');

        $query = School::with('user');

        if ($tab === 'verified') {
            $query->where('verification_status', 'verified');
        } elseif ($tab === 'rejected') {
            $query->where('verification_status', 'rejected');
        } else {
            $query->where('verification_status', 'pending');
        }

        $schools = $query->latest()->paginate(10);

        $counts = [
            'pending' => School::where('verification_status', 'pending')->count(),
            'verified' => School::where('verification_status', 'verified')->count(),
            'rejected' => School::where('verification_status', 'rejected')->count(),
        ];

        return view('admin.schools.index', compact('schools', 'tab', 'counts'));
    }

    public function show(School $school): View
    {
        $school->load('user');

        return view('admin.schools.show', compact('school'));
    }

    public function verify(School $school): RedirectResponse
    {
        $school->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        Log::info('School verified by admin', [
            'school_id' => $school->id,
            'admin_id' => auth()->id(),
        ]);

        return redirect()->route('admin.schools.show', $school)
            ->with('success', "School '{$school->name}' has been verified successfully.");
    }

    public function reject(School $school, Request $request): RedirectResponse
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $school->update([
            'verification_status' => 'rejected',
            'verified_at' => null,
        ]);

        Log::info('School rejected by admin', [
            'school_id' => $school->id,
            'admin_id' => auth()->id(),
            'reason' => $request->reason,
        ]);

        return redirect()->route('admin.schools.show', $school)
            ->with('success', "School '{$school->name}' has been rejected.");
    }
}
