<?php

namespace App\Http\Controllers\Donor;

use App\Contracts\Services\StellarServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(
        private readonly StellarServiceInterface $stellarService
    ) {}

    public function bind(Request $request): RedirectResponse
    {
        $request->validate([
            'wallet_address' => 'required|string|max:56',
        ]);

        $user = auth()->user();
        $user->update(['wallet_address' => $request->wallet_address]);

        return back()->with('success', 'Wallet address linked successfully.');
    }

    public function getBalance(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user->wallet_address) {
            return response()->json([
                'balance' => 0,
                'address' => null,
                'error' => 'No wallet address linked.',
            ]);
        }

        $balance = $this->stellarService->getBalance($user->wallet_address);

        return response()->json([
            'balance' => $balance,
            'address' => $user->wallet_address,
            'error' => null,
        ]);
    }
}
