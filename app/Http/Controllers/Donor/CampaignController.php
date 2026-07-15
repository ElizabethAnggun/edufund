<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(): View
    {
        return view('donor.campaigns.index');
    }

    public function show(): View
    {
        return view('donor.campaigns.show');
    }
}
