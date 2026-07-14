<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function index(): View
    {
        // Placeholder - will be implemented with actual service
        return view('donor.donations.index', [
            'donations' => collect()
        ]);
    }

    public function show($id): View
    {
        // Placeholder - will be implemented with actual service
        return view('donor.donations.show', [
            'donation' => null
        ]);
    }
}