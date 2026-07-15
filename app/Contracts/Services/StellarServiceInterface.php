<?php

namespace App\Contracts\Services;

interface StellarServiceInterface
{
    public function getBalance(string $walletAddress): float;
    public function verifyTransaction(string $txHash, float $expectedAmount): bool;
    public function createEscrowAccount(string $campaignId): string;
    public function submitTransaction(string $signedXdr): array;
}