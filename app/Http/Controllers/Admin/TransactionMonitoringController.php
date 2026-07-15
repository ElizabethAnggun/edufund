<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Services\StellarServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\BlockchainTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransactionMonitoringController extends Controller
{
    public function __construct(
        private readonly StellarServiceInterface $stellarService
    ) {}

    public function index(): View
    {
        $transactions = BlockchainTransaction::with(['user', 'transactionable'])
            ->latest()
            ->paginate(20);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(BlockchainTransaction $transaction): View
    {
        $transaction->load(['user', 'transactionable']);

        return view('admin.transactions.show', compact('transaction'));
    }

    public function retry(BlockchainTransaction $transaction): RedirectResponse
    {
        if ($transaction->status->value !== 'pending') {
            return back()->with('error', 'Only pending transactions can be retried.');
        }

        $verified = $this->stellarService->verifyTransaction(
            $transaction->tx_hash,
            (float) $transaction->amount
        );

        if ($verified) {
            $transaction->update([
                'status' => 'successful',
                'confirmed_at' => now(),
            ]);

            $transactionable = $transaction->transactionable;
            if ($transactionable && method_exists($transactionable, 'status')) {
                if (class_basename($transactionable) === 'Donation') {
                    $transactionable->update(['status' => 'completed']);
                    $transactionable->campaign()->increment('current_amount', $transactionable->amount);
                }
            }

            return back()->with('success', 'Transaction verified and updated successfully.');
        }

        return back()->with('error', 'Transaction could not be verified on the Stellar network.');
    }
}
