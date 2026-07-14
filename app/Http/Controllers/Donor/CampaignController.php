<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(): View
    {
        // Placeholder - will be implemented with actual service
        return view('donor.campaigns.index', [
            'campaigns' => collect()
        ]);
    }

    public function show($id): View
    {
        // Placeholder - will be implemented with actual service
        return view('donor.campaigns.show', [
            'campaign' => null
        ]);
    }

    public function saved(): View
    {
        // Placeholder - will be implemented with actual service
        return view('donor.saved-campaigns', [
            'campaigns' => collect()
        ]);
    }

    public function save($id)
    {
        // Placeholder
        return back();
    }

    public function unsave($id)
    {
        // Placeholder
        return back();
    }
}