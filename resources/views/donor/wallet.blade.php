@extends('layouts.donor')

@section('title', 'Stellar Wallet')
@section('page-title', 'Stellar Wallet')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Your Stellar Wallet</h2>
        <p class="text-neutral-500">Connect your Freighter wallet to start donating</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Balance Card -->
        <div class="lg:col-span-2 bg-gradient-to-br from-primary to-primary-active p-8 rounded-xl text-white">
            <p class="text-white/80 text-sm mb-2">XLM Balance</p>
            <h3 class="text-4xl font-bold mb-6" id="wallet-balance">
                {{ $balance ?? 0 }} XLM
            </h3>
            <div class="flex gap-3">
                <button id="btn-connect" onclick="connectFreighter()" class="bg-white text-primary px-6 py-2.5 rounded-full font-semibold text-sm hover:bg-white/90 transition-all">
                    Connect Freighter
                </button>
                <button onclick="refreshBalance()" class="bg-white/20 text-white px-6 py-2.5 rounded-full font-semibold text-sm hover:bg-white/30 transition-all">
                    Refresh Balance
                </button>
            </div>
        </div>

        <!-- Wallet Info -->
        <div class="bg-surface p-6 rounded-xl border border-neutral-200">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4">Wallet Details</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Wallet Address</p>
                    <p class="text-sm font-mono text-neutral-900 break-all" id="wallet-address">
                        {{ $walletAddress ?? 'Not connected' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Network</p>
                    <p class="text-sm font-semibold text-neutral-900">Stellar Testnet</p>
                </div>
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Status</p>
                    <span id="wallet-status" class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $walletAddress ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $walletAddress ? 'Connected' : 'Not Connected' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        <h3 class="text-xl font-semibold text-neutral-900 mb-4">How It Works</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-background p-6 rounded-xl text-center">
                <div class="w-12 h-12 bg-primary-soft rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                <h4 class="font-semibold text-neutral-900 mb-2">1. Connect</h4>
                <p class="text-sm text-neutral-500">Install Freighter browser extension and connect your Stellar wallet.</p>
            </div>
            <div class="bg-background p-6 rounded-xl text-center">
                <div class="w-12 h-12 bg-primary-soft rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4 class="font-semibold text-neutral-900 mb-2">2. Fund</h4>
                <p class="text-sm text-neutral-500">Fund your wallet with XLM to donate to students' education.</p>
            </div>
            <div class="bg-background p-6 rounded-xl text-center">
                <div class="w-12 h-12 bg-primary-soft rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h4 class="font-semibold text-neutral-900 mb-2">3. Donate</h4>
                <p class="text-sm text-neutral-500">Choose a campaign and donate directly to support education.</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    async function connectFreighter() {
        try {
            if (typeof window.freighter === 'undefined') {
                alert('Please install Freighter browser extension first.\n\nhttps://freighter.app');
                return;
            }

            const address = await window.freighter.getAddress();
            if (address) {
                const response = await fetch('{{ route("donor.wallet.bind") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ wallet_address: address }),
                });

                const data = await response.json();

                if (response.ok) {
                    document.getElementById('wallet-address').textContent = address;
                    document.getElementById('wallet-status').textContent = 'Connected';
                    document.getElementById('wallet-status').className = 'inline-block px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
                    document.getElementById('btn-connect').textContent = 'Connected';
                    document.getElementById('btn-connect').disabled = true;

                    alert('Wallet connected successfully!');
                    refreshBalance();
                } else {
                    alert('Failed to save wallet address: ' + (data.message || 'Unknown error'));
                }
            }
        } catch (error) {
            console.error('Freighter connection error:', error);
            alert('Failed to connect wallet. Please ensure Freighter is installed and unlocked.');
        }
    }

    async function refreshBalance() {
        try {
            const response = await fetch('{{ route("donor.wallet.balance") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            });

            const data = await response.json();
            document.getElementById('wallet-balance').textContent = data.balance.toFixed(7) + ' XLM';

            if (data.address) {
                document.getElementById('wallet-address').textContent = data.address;
            }
        } catch (error) {
            console.error('Balance refresh error:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        refreshBalance();
    });
</script>
@endpush