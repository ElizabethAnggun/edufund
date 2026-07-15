<?php

namespace Tests\Stubs;

use App\Contracts\Services\StellarServiceInterface;
use App\Models\Campaign;
use App\Models\Milestone;

/**
 * In-memory fake implementation of the Stellar service used by the test suite
 * so feature tests never hit the real Stellar / Soroban network.
 */
class FakeStellarService implements StellarServiceInterface
{
    /** @var bool Controls the result of verifyTransaction() for a given test. */
    public static bool $verifyTransactionResult = true;

    public function getBalance(string $walletAddress): float
    {
        return 1000.0;
    }

    public function verifyTransaction(string $txHash, float $expectedAmount): bool
    {
        return self::$verifyTransactionResult;
    }

    public function submitTransaction(string $signedXdr): array
    {
        return ['hash' => 'TXFAKE' . substr(md5($signedXdr), 0, 10)];
    }

    public function createEscrowAccount(Campaign $campaign): string
    {
        return 'GESCROWFAKEADDRESS';
    }

    public function depositToEscrow(Campaign $campaign, string $donorPublicKey, float $amount): array
    {
        return ['hash' => 'TXFAKEDEPOSIT'];
    }

    public function releaseFunds(Campaign $campaign, Milestone $milestone, string $recipientAddress): array
    {
        return ['success' => true, 'hash' => 'TXFAKERELEASE'];
    }

    public function refundDonors(Campaign $campaign): array
    {
        return ['success' => true];
    }

    public function getEscrowBalance(Campaign $campaign): float
    {
        return 0.0;
    }
}
