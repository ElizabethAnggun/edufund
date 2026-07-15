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

        foreach ($campaigns as $campaign) {
            foreach ($donors as $donor) {
                $amount = rand(100000, 1000000);

                $donation = Donation::firstOrCreate(
                    [
                        'donor_id' => $donor->id,
                        'campaign_id' => $campaign->id,
                        'amount' => $amount,
                    ],
                    [
                        'currency' => 'XLM',
                        'status' => DonationStatus::COMPLETED,
                        'message' => 'Semangat belajar!',
                        'anonymous' => false,
                    ]
                );

                BlockchainTransaction::firstOrCreate(
                    ['transactionable_id' => $donation->id, 'transactionable_type' => Donation::class],
                    [
                        'user_id' => $donor->id,
                        'tx_hash' => 'TX' . strtoupper(uniqid()),
                        'type' => BlockchainTransactionType::DONATION,
                        'amount' => $amount,
                        'currency' => 'XLM',
                        'from_address' => 'GDONOR' . strtoupper(uniqid()),
                        'to_address' => config('services.stellar.escrow_account'),
                        'status' => BlockchainTransactionStatus::SUCCESSFUL,
                        'network' => BlockchainNetwork::TESTNET,
                        'confirmed_at' => now(),
                    ]
                );
            }
        }
    }
}
