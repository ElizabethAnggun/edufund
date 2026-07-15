@extends('layouts.donor')

@section('title', 'Donate to Campaign')
@section('page-title', 'Donate')

@section('content')
    <div class="mb-6">
        <a href="{{ route('donor.campaigns.show', $campaign) }}" class="text-primary hover:text-primary-hover font-medium text-sm">
            ← Back to Campaign
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Donation Form -->
        <div class="lg:col-span-2">
            <div class="bg-surface p-8 rounded-xl border border-neutral-200">
                <h2 class="text-2xl font-bold text-neutral-900 mb-6">Make a Donation</h2>

                @if(!$walletAddress)
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <div>
                                <p class="font-semibold text-yellow-800">No wallet connected</p>
                                <p class="text-sm text-yellow-700">You need to connect your Freighter wallet first.</p>
                                <a href="{{ route('donor.wallet') }}" class="text-sm font-medium text-yellow-800 underline mt-1 inline-block">Connect Wallet →</a>
                            </div>
                        </div>
                    </div>
                @endif

                <div id="donation-form">
                    <div class="mb-4">
                        <label class="block text-neutral-700 text-sm font-medium mb-2">Donation Amount (XLM)</label>
                        <div class="grid grid-cols-4 gap-3 mb-3">
                            <button type="button" onclick="setAmount(5)" class="px-4 py-3 border border-neutral-200 rounded-xl hover:border-primary hover:bg-primary-soft transition-all text-sm font-medium">5 XLM</button>
                            <button type="button" onclick="setAmount(10)" class="px-4 py-3 border border-neutral-200 rounded-xl hover:border-primary hover:bg-primary-soft transition-all text-sm font-medium">10 XLM</button>
                            <button type="button" onclick="setAmount(25)" class="px-4 py-3 border border-neutral-200 rounded-xl hover:border-primary hover:bg-primary-soft transition-all text-sm font-medium">25 XLM</button>
                            <button type="button" onclick="setAmount(50)" class="px-4 py-3 border border-neutral-200 rounded-xl hover:border-primary hover:bg-primary-soft transition-all text-sm font-medium">50 XLM</button>
                        </div>
                        <input type="number" id="amount" name="amount" step="0.0000001" min="0.1" required
                               class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all text-lg"
                               placeholder="0.00">
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="anonymous" name="anonymous" class="w-4 h-4 text-primary rounded border-neutral-300 focus:ring-primary">
                            <span class="text-sm text-neutral-700">Donate anonymously</span>
                        </label>
                    </div>

                    <div class="mb-6">
                        <label class="block text-neutral-700 text-sm font-medium mb-2">Message (Optional)</label>
                        <textarea id="message" name="message" rows="3"
                                  class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none"
                                  placeholder="Write an encouraging message for the student..."></textarea>
                    </div>

                    <button id="btn-donate" onclick="initiateDonation()" {{ !$walletAddress ? 'disabled' : '' }}
                            class="w-full bg-gradient-to-r from-primary to-primary-hover hover:from-primary-hover hover:to-primary-active text-white font-semibold py-3 px-4 rounded-full transition-all btn-press shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                        Pay with Freighter
                    </button>
                </div>

                <!-- Transaction Status -->
                <div id="tx-status" class="mt-4 hidden">
                    <div class="p-4 rounded-xl" id="tx-status-content">
                        <p id="tx-status-text" class="text-sm"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaign Summary -->
        <div class="lg:col-span-1">
            <div class="bg-surface p-6 rounded-xl border border-neutral-200 sticky top-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Campaign Summary</h3>

                <div class="mb-4">
                    <h4 class="font-semibold text-neutral-900 mb-1">{{ $campaign->title }}</h4>
                    <p class="text-sm text-neutral-500">{{ $campaign->school->name ?? 'School' }}</p>
                </div>

                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-neutral-600">Progress</span>
                        <span class="text-sm font-semibold text-primary">
                            {{ $campaign->goal_amount > 0 ? round(($campaign->current_amount / $campaign->goal_amount) * 100, 1) : 0 }}%
                        </span>
                    </div>
                    <div class="w-full bg-neutral-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-primary to-primary-hover h-2 rounded-full" style="width: {{ $campaign->goal_amount > 0 ? min(($campaign->current_amount / $campaign->goal_amount) * 100, 100) : 0 }}%"></div>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-4 pb-4 border-b border-neutral-200">
                    <div>
                        <p class="text-xs text-neutral-500">Raised</p>
                        <p class="text-lg font-bold text-primary">{{ number_format($campaign->current_amount, 7) }} XLM</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-neutral-500">Goal</p>
                        <p class="text-sm font-semibold text-neutral-700">{{ number_format($campaign->goal_amount, 7) }} XLM</p>
                    </div>
                </div>

                <div class="p-4 bg-primary-soft rounded-xl">
                    <p class="text-sm text-neutral-700">
                        <svg class="w-5 h-5 inline-block mr-1 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.053-.382-3.016z"/>
                        </svg>
                        <strong>Secure & Transparent</strong><br>
                        Your donation is recorded on the Stellar blockchain.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const CAMPAIGN_ID = {{ $campaign->id }};
    const ESCROW_ADDRESS = '{{ config("services.stellar.escrow_account", "") }}';

    function setAmount(amount) {
        document.getElementById('amount').value = amount;
    }

    async function initiateDonation() {
        const amount = parseFloat(document.getElementById('amount').value);
        const message = document.getElementById('message').value;
        const anonymous = document.getElementById('anonymous').checked;

        if (!amount || amount < 0.1) {
            alert('Please enter a valid donation amount (minimum 0.1 XLM).');
            return;
        }

        const btn = document.getElementById('btn-donate');
        const txStatus = document.getElementById('tx-status');
        const txStatusContent = document.getElementById('tx-status-content');
        const txStatusText = document.getElementById('tx-status-text');

        btn.disabled = true;
        btn.textContent = 'Processing...';
        txStatus.classList.remove('hidden');
        txStatusContent.className = 'p-4 rounded-xl bg-yellow-50 border border-yellow-200';
        txStatusText.textContent = 'Please sign the transaction in your Freighter wallet...';
        txStatusText.className = 'text-sm text-yellow-800';

        try {
            if (typeof window.freighter === 'undefined') {
                throw new Error('Freighter wallet not found. Please install Freighter extension.');
            }

            const fromAddress = await window.freighter.getAddress();

            const stellarTx = await window.freighter.signTransaction(
                createTransactionXDR(fromAddress, ESCROW_ADDRESS, amount.toString()),
                { networkPassphrase: 'Test SDF Network ; September 2015' }
            );

            const submitResponse = await fetch('/api/stellar/submit-transaction', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ signed_xdr: stellarTx }),
            });

            const submitData = await submitResponse.json();

            if (submitData.success) {
                const confirmResponse = await fetch('{{ route("donor.campaigns.donate.confirm", $campaign) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        tx_hash: submitData.hash,
                        from_address: fromAddress,
                        amount: amount,
                        message: message,
                        anonymous: anonymous,
                    }),
                });

                const confirmData = await confirmResponse.json();

                if (confirmResponse.ok) {
                    txStatusContent.className = 'p-4 rounded-xl bg-green-50 border border-green-200';
                    txStatusText.textContent = 'Donation completed successfully! Redirecting...';
                    txStatusText.className = 'text-sm text-green-800';

                    setTimeout(() => {
                        window.location.href = '{{ route("donor.donations.index") }}';
                    }, 2000);
                } else {
                    throw new Error(confirmData.message || 'Failed to record donation.');
                }
            } else {
                throw new Error(submitData.error || 'Transaction failed on Stellar network.');
            }
        } catch (error) {
            console.error('Donation error:', error);
            txStatusContent.className = 'p-4 rounded-xl bg-red-50 border border-red-200';
            txStatusText.textContent = 'Error: ' + error.message;
            txStatusText.className = 'text-sm text-red-800';
            btn.disabled = false;
            btn.textContent = 'Pay with Freighter';
        }
    }

    function createTransactionXDR(from, to, amount) {
        const tx = new StellarSdk.TransactionBuilder(
            new StellarSdk.Account(from, '-1'),
            {
                fee: '100',
                networkPassphrase: 'Test SDF Network ; September 2015',
            }
        )
        .addOperation(StellarSdk.Operation.payment({
            destination: to,
            asset: StellarSdk.Asset.native(),
            amount: amount,
        }))
        .addMemo(StellarSdk.Memo.text('EduFund Donation'))
        .setTimeout(180)
        .build();

        return tx.toXDR();
    }
</script>
@endpush