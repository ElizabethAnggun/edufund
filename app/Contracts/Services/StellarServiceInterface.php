<?php

namespace App\Contracts\Services;

use App\Models\Campaign;
use App\Models\Milestone;

interface StellarServiceInterface
{
    // Classic Stellar (Horizon) Methods
    public function getBalance(string $walletAddress): float;
    public function verifyTransaction(string $txHash, float $expectedAmount): bool;
    public function submitTransaction(string $signedXdr): array;

    // Stellar 3.0 / Soroban Smart Contract Methods
    public function createEscrowAccount(Campaign $campaign): string;
    public function depositToEscrow(Campaign $campaign, string $donorPublicKey, float $amount): array;
    public function releaseFunds(Campaign $campaign, Milestone $milestone, string $recipientAddress): array;
    public function refundDonors(Campaign $campaign): array;
    public function getEscrowBalance(Campaign $campaign): float;
}
