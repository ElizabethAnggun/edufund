<?php

namespace App\Http\Controllers\Donor;

use App\Contracts\Services\DonationServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function __construct(
        private readonly DonationServiceInterface $donationService
    ) {}

    public function index(): View
    {
        $donations = $this->donationService->getDonorDonations(auth()->user());

        return view('donor.donations.index', [
            'donations' => $donations
        ]);
    }

    public function show(Donation $donation): View
    {
        if ($donation->donor_id !== auth()->id()) {
            abort(403);
        }

        $donation->load([
            'campaign.school',
            'campaign.fundingRequest.studentProfile.user',
            'blockchainTransaction',
        ]);

        return view('donor.donations.show', [
            'donation' => $donation
        ]);
    }
}
