<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SavedCampaignController extends Controller
{
    public function index(): View
    {
        return view('donor.saved-campaigns');
    }
}
