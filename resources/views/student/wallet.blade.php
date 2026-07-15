@extends('layouts.student')

@section('title', 'Wallet')
@section('page-title', 'My Wallet')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Your Wallet</h2>
        <p class="text-neutral-500">Manage your Stellar wallet for receiving funding</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Balance Card -->
        <div class="lg:col-span-2 bg-gradient-to-br from-primary to-primary-active p-8 rounded-xl text-white">
            <p class="text-white/80 text-sm mb-2">Total Balance</p>
            <h3 class="text-4xl font-bold mb-6">${{ number_format($wallet['balance'] ?? 0, 2) }}</h3>
            <div class="flex gap-3">
                <button disabled class="bg-white/50 text-white px-6 py-2.5 rounded-full font-semibold text-sm cursor-not-allowed">
                    Connect Wallet (Coming Soon)
                </button>
            </div>
        </div>

        <!-- Wallet Info -->
        <div class="bg-surface p-6 rounded-xl border border-neutral-200">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4">Wallet Details</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Wallet Address</p>
                    <p class="text-sm font-mono text-neutral-900 break-all">{{ $wallet['address'] ?? 'G...' }}</p>
                </div>
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Network</p>
                    <p class="text-sm font-semibold text-neutral-900">{{ $wallet['network'] ?? 'Stellar' }}</p>
                </div>
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Status</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Pending Connection
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection
