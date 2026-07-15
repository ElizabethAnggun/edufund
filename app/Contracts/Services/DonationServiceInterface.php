<?php

namespace App\Contracts\Services;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Donation;

interface DonationServiceInterface
{
    public function createDonation(User $donor, Campaign $campaign, array $data): Donation;
    public function confirmDonation(Donation $donation, string $txHash, string $fromAddress): bool;
    public function getDonorDonations(User $donor, int $limit = 15);
}
