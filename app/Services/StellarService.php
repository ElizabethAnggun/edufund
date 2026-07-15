<?php

namespace App\Services;

use App\Contracts\Services\StellarServiceInterface;
use App\Models\Campaign;
use App\Models\Milestone;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StellarService implements StellarServiceInterface
{
    private string $horizonUrl;
    private string $networkPassphrase;
    private string $escrowAccount;

    public function __construct()
    {
        $this->horizonUrl = config('services.stellar.horizon_url', 'https://horizon-testnet.stellar.org');
        $this->networkPassphrase = config('services.stellar.network_passphrase', 'Test SDF Network ; September 2015');
        $this->escrowAccount = config('services.stellar.escrow_account', 'GDEMOESCROWACCOUNT1234567890123456789012345678901234');
    }

    public function getBalance(string $walletAddress): float
    {
        try {
            $response = Http::timeout(10)->get("{$this->horizonUrl}/accounts/{$walletAddress}");

            if (!$response->successful()) {
                Log::warning('Failed to fetch Stellar balance', [
                    'address' => $walletAddress,
                    'status' => $response->status(),
                ]);
                return 0.0;
            }

            $data = $response->json();
            $nativeBalance = collect($data['balances'] ?? [])
                ->firstWhere('asset_type', 'native');

            return $nativeBalance ? (float) $nativeBalance['balance'] : 0.0;
        } catch (\Exception $e) {
            Log::error('Stellar balance fetch error', [
                'address' => $walletAddress,
                'error' => $e->getMessage(),
            ]);
            return 0.0;
        }
    }

    public function verifyTransaction(string $txHash, float $expectedAmount): bool
    {
        try {
            $response = Http::timeout(10)->get("{$this->horizonUrl}/transactions/{$txHash}");

            if (!$response->successful()) {
                Log::warning('Failed to verify Stellar transaction', [
                    'tx_hash' => $txHash,
                    'status' => $response->status(),
                ]);
                return false;
            }

            $data = $response->json();

            $operationsResponse = Http::timeout(10)->get("{$this->horizonUrl}/transactions/{$txHash}/operations");
            if (!$operationsResponse->successful()) {
                return false;
            }

            $operations = $operationsResponse->json()['_embedded']['records'] ?? [];

            foreach ($operations as $operation) {
                if (
                    ($operation['type'] === 'payment' || $operation['type_i'] === 1) &&
                    $operation['asset_type'] === 'native' &&
                    (float) $operation['amount'] >= $expectedAmount - 0.0000001 &&
                    $operation['to'] === $this->escrowAccount
                ) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Stellar transaction verification error', [
                'tx_hash' => $txHash,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function submitTransaction(string $signedXdr): array
    {
        try {
            $response = Http::timeout(30)->post("{$this->horizonUrl}/transactions", [
                'tx' => $signedXdr,
            ]);

            if (!$response->successful()) {
                $error = $response->json();
                Log::error('Stellar transaction submission failed', [
                    'error' => $error,
                ]);
                return [
                    'success' => false,
                    'hash' => null,
                    'error' => $error['extras']['result_codes']['operations'][0] ?? 'submission_failed',
                ];
            }

            $data = $response->json();
            return [
                'success' => true,
                'hash' => $data['hash'] ?? null,
                'error' => null,
            ];
        } catch (\Exception $e) {
            Log::error('Stellar transaction submission error', [
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'hash' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    // ========================================================================
    // Stellar Classic (Legacy) - Soroban methods not supported
    // ========================================================================

    public function createEscrowAccount(Campaign $campaign): string
    {
        // Classic Stellar: just return the configured escrow account
        return $this->escrowAccount;
    }

    public function depositToEscrow(Campaign $campaign, string $donorPublicKey, float $amount): array
    {
        // Classic Stellar: deposit is handled manually by donor via Horizon transaction
        return [
            'success' => true,
            'hash' => null,
            'error' => null,
            'note' => 'Classic Stellar: deposit handled via Horizon transaction',
        ];
    }

    public function releaseFunds(Campaign $campaign, Milestone $milestone, string $recipientAddress): array
    {
        // Classic Stellar: release is not supported via smart contract
        return [
            'success' => false,
            'hash' => null,
            'error' => 'Classic Stellar does not support smart contract fund releases. Upgrade to Soroban.',
        ];
    }

    public function refundDonors(Campaign $campaign): array
    {
        // Classic Stellar: refund not supported
        return [
            'success' => false,
            'hash' => null,
            'error' => 'Classic Stellar does not support smart contract refunds. Upgrade to Soroban.',
        ];
    }

    public function getEscrowBalance(Campaign $campaign): float
    {
        // Classic Stellar: check the escrow account balance
        if (!empty($this->escrowAccount)) {
            return $this->getBalance($this->escrowAccount);
        }
        return 0.0;
    }
}
