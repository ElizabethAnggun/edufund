<?php

namespace App\Services;

use App\Contracts\Services\DonationServiceInterface;
use App\Contracts\Services\StellarServiceInterface;
use App\Enums\BlockchainNetwork;
use App\Enums\BlockchainTransactionStatus;
use App\Enums\BlockchainTransactionType;
use App\Enums\DonationStatus;
use App\Models\BlockchainTransaction;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DonationService implements DonationServiceInterface
{
    public function __construct(
        private readonly StellarServiceInterface $stellarService
    ) {}

    public function createDonation(User $donor, Campaign $campaign, array $data): Donation
    {
        return DB::transaction(function () use ($donor, $campaign, $data) {
            $donation = Donation::create([
                'donor_id' => $donor->id,
                'campaign_id' => $campaign->id,
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'XLM',
                'status' => DonationStatus::PENDING,
                'message' => $data['message'] ?? null,
                'anonymous' => $data['anonymous'] ?? false,
            ]);

            $escrowAddress = config('services.stellar.escrow_account', '');

            BlockchainTransaction::create([
                'transactionable_id' => $donation->id,
                'transactionable_type' => Donation::class,
                'user_id' => $donor->id,
                'tx_hash' => 'pending_' . $donation->id,
                'type' => BlockchainTransactionType::DONATION,
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'XLM',
                'from_address' => $donor->wallet_address ?? '',
                'to_address' => $escrowAddress,
                'status' => BlockchainTransactionStatus::PENDING,
                'network' => BlockchainNetwork::TESTNET,
            ]);

            return $donation;
        });
    }

    public function confirmDonation(Donation $donation, string $txHash, string $fromAddress): bool
    {
        $verified = $this->stellarService->verifyTransaction($txHash, (float) $donation->amount);

        return DB::transaction(function () use ($donation, $txHash, $fromAddress, $verified) {
            $blockchainTx = $donation->blockchainTransaction;

            if ($verified) {
                $donation->update(['status' => DonationStatus::COMPLETED]);

                $donation->campaign()->increment('current_amount', $donation->amount);

                $campaign = $donation->campaign;
                if ($campaign->funding_request_id) {
                    $campaign->fundingRequest()->increment('current_amount', $donation->amount);
                }

                if ($blockchainTx) {
                    $blockchainTx->update([
                        'tx_hash' => $txHash,
                        'from_address' => $fromAddress,
                        'status' => BlockchainTransactionStatus::SUCCESSFUL,
                        'confirmed_at' => now(),
                    ]);
                }

                if ($campaign->current_amount >= $campaign->goal_amount) {
                    $campaign->update(['status' => 'completed']);
                }

                return true;
            } else {
                $donation->update(['status' => DonationStatus::FAILED]);

                if ($blockchainTx) {
                    $blockchainTx->update([
                        'tx_hash' => $txHash,
                        'status' => BlockchainTransactionStatus::FAILED,
                        'error_message' => 'Transaction verification failed on Stellar network',
                    ]);
                }

                return false;
            }
        });
    }

    public function getDonorDonations(User $donor, int $limit = 15)
    {
        return $donor->donations()
            ->with(['campaign.school', 'campaign.fundingRequest.studentProfile.user'])
            ->latest()
            ->paginate($limit);
    }
}
