<?php

namespace Database\Seeders;

use App\Enums\BlockchainNetwork;
use App\Enums\BlockchainTransactionStatus;
use App\Enums\BlockchainTransactionType;
use App\Enums\DonationStatus;
use App\Models\BlockchainTransaction;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $donors = User::role('donor')->get();
        $campaigns = Campaign::all();

        if ($donors->isEmpty() || $campaigns->isEmpty()) {
            return;
        }

        $messages = [
            'Semangat belajar! Jangan pernah menyerah.',
            'Semoga membantu. Terus berusaha dan capai cita-citamu!',
            'Donasi ini untuk mendukung pendidikanmu. Sukses selalu!',
            'Bismillah, semoga menjadi amal jariyah.',
            'Mari kita dukung pendidikan bersama. Good luck!',
            'Setiap langkah kecil toward pendidikan adalah investasi untuk masa depan.',
            'Donasi dari hati. Semoga bermanfaat.',
            'Support dari saya. Keep fighting!',
        ];

        foreach ($donors as $donor) {
            // Each donor donates to 3-6 random campaigns
            $numDonations = rand(3, 6);
            $selectedCampaigns = $campaigns->random(min($numDonations, $campaigns->count()));

            foreach ($selectedCampaigns as $campaign) {
                $amount = rand(50000, 5000000); // 50K to 5M
                $anonymous = rand(0, 100) > 80; // 20% chance of anonymous
                $message = $anonymous ? null : $messages[array_rand($messages)];
                
                $statuses = [
                    DonationStatus::COMPLETED,
                    DonationStatus::COMPLETED,
                    DonationStatus::COMPLETED,
                    DonationStatus::PENDING,
                    DonationStatus::FAILED,
                ];
                $status = $statuses[array_rand($statuses)];

                $donation = Donation::create([
                    'donor_id' => $donor->id,
                    'campaign_id' => $campaign->id,
                    'amount' => $amount,
                    'currency' => 'IDR',
                    'status' => $status,
                    'message' => $message,
                    'anonymous' => $anonymous,
                ]);

                // Create blockchain transaction for completed and pending donations
                if ($status === DonationStatus::COMPLETED || $status === DonationStatus::PENDING) {
                    $txStatus = $status === DonationStatus::COMPLETED 
                        ? BlockchainTransactionStatus::SUCCESSFUL 
                        : BlockchainTransactionStatus::PENDING;
                    
                    $confirmedAt = $status === DonationStatus::COMPLETED 
                        ? now()->subDays(rand(1, 30)) 
                        : null;

                    BlockchainTransaction::create([
                        'transactionable_id' => $donation->id,
                        'transactionable_type' => Donation::class,
                        'user_id' => $donor->id,
                        'tx_hash' => 'TX' . strtoupper(\Illuminate\Support\Str::random(64)),
                        'type' => BlockchainTransactionType::DONATION,
                        'amount' => $amount,
                        'currency' => 'IDR',
                        'from_address' => $donor->wallet_address ?? 'GDONOR' . strtoupper(\Illuminate\Support\Str::random(55)),
                        'to_address' => config('services.stellar.escrow_account', 'GESCROWACCOUNTADDRESS000000000000000000000000000000000000'),
                        'status' => $txStatus,
                        'network' => BlockchainNetwork::TESTNET,
                        'confirmed_at' => $confirmedAt,
                    ]);
                }
            }

            // Create saved campaigns (bookmarks) for each donor
            $numSaved = rand(2, 5);
            $savedCampaigns = $campaigns->random(min($numSaved, $campaigns->count()));
            
            foreach ($savedCampaigns as $campaign) {
                $donor->savedCampaigns()->syncWithoutDetaching([$campaign->id]);
            }
        }
    }
}
