<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CampaignManagementController extends Controller
{
    public function index(): View
    {
        $campaigns = Campaign::with(['school', 'fundingRequest.studentProfile.user'])
            ->latest()
            ->paginate(15);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function toggleStatus(Campaign $campaign): RedirectResponse
    {
        $newStatus = $campaign->status->value === 'active' ? 'paused' : 'active';
        $campaign->update(['status' => $newStatus]);

        return redirect()->route('admin.campaigns.index')
            ->with('success', "Campaign '{$campaign->title}' status changed to {$newStatus}.");
    }
}
