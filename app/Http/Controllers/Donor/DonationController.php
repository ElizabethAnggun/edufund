<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function index(): View
    {
        return view('donor.donations.index');
    }
}
