@extends('layouts.donor')

@section('title', 'Wallet')
@section('page-title', 'My Wallet')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Your Wallet</h2>
        <p class="text-neutral-500">Manage your Stellar wallet for donations</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Balance Card -->
        <div class="lg:col-span-2 bg-gradient-to-br from-primary to-primary-active p-8 rounded-xl text-white">
            <p class="text-white/80 text-sm mb-2">Total Balance</p>
            <h3 class="text-4xl font-bold mb-6">${{ number_format($balance ?? 0, 2) }}</h3>
            <div class="flex gap-3">
                <button class="bg-white text-primary px-6 py-2.5 rounded-full font-semibold text-sm hover:bg-white/90 transition-all">
                    Deposit
                </button>
                <button class="bg-white/20 text-white px-6 py-2.5 rounded-full font-semibold text-sm hover:bg-white/30 transition-all">
                    Withdraw
                </button>
            </div>
        </div>

        <!-- Wallet Info -->
        <div class="bg-surface p-6 rounded-xl border border-neutral-200">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4">Wallet Details</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Wallet Address</p>
                    <p class="text-sm font-mono text-neutral-900 break-all">{{ $walletAddress ?? 'G...' }}</p>
                </div>
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Network</p>
                    <p class="text-sm font-semibold text-neutral-900">Stellar Testnet</p>
                </div>
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Status</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Active
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        <h3 class="text-xl font-semibold text-neutral-900 mb-6">Recent Transactions</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-background rounded-xl">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-neutral-900">Donation to Sarah Johnson</p>
                        <p class="text-sm text-neutral-500">Today, 2:30 PM</p>
                    </div>
                </div>
                <p class="text-lg font-bold text-green-600">-$100.00</p>
            </div>

            <div class="flex items-center justify-between p-4 bg-background rounded-xl">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-neutral-900">Donation to Marcus Chen</p>
                        <p class="text-sm text-neutral-500">Yesterday, 10:15 AM</p>
                    </div>
                </div>
                <p class="text-lg font-bold text-green-600">-$50.00</p>
            </div>
        </div>
    </div>
@endsection