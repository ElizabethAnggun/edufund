@extends('layouts.donor')

@section('title', 'Donation Details')
@section('page-title', 'Donation Details')

@section('content')
    <div class="mb-6">
        <a href="{{ route('donor.donations.index') }}" class="text-primary hover:text-primary-hover font-medium text-sm">
            ← Back to Donations
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface p-8 rounded-xl border border-neutral-200">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-neutral-900 mb-1">
                            {{ $donation->anonymous ? 'Anonymous Donor' : ($donation->campaign->fundingRequest->studentProfile->user->name ?? 'Student') }}
                        </h2>
                        <p class="text-neutral-500">Donation to "{{ $donation->campaign->title ?? 'Campaign #' . $donation->campaign_id }}"</p>
                    </div>
                    @php
                        $statusClass = match($donation->status->value ?? $donation->status) {
                            'completed' => 'bg-green-100 text-green-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'failed' => 'bg-red-100 text-red-800',
                            default => 'bg-neutral-100 text-neutral-800'
                        };
                    @endphp
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                        {{ ucfirst($donation->status->value ?? $donation->status) }}
                    </span>
                </div>

                <div class="mb-6">
                    <p class="text-3xl font-bold text-primary">{{ number_format($donation->amount, 7) }} {{ $donation->currency }}</p>
                </div>

                @if($donation->message)
                    <div class="p-4 bg-background rounded-xl">
                        <p class="text-sm text-neutral-500 mb-1">Your Message</p>
                        <p class="text-sm text-neutral-700 italic">"{{ $donation->message }}"</p>
                    </div>
                @endif
            </div>

            <div class="bg-surface p-8 rounded-xl border border-neutral-200">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Campaign Details</h3>
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary to-primary-active flex items-center justify-center text-white font-bold text-xl">
                        {{ substr($donation->campaign->fundingRequest->studentProfile->user->name ?? 'S', 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-neutral-900 mb-1">{{ $donation->campaign->title }}</h4>
                        <p class="text-sm text-neutral-500">{{ $donation->campaign->school->name ?? 'N/A' }}</p>
                        <a href="{{ route('donor.campaigns.show', $donation->campaign) }}" class="text-sm text-primary hover:text-primary-hover font-medium mt-2 inline-block">View Campaign →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-surface p-6 rounded-xl border border-neutral-200 sticky top-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Blockchain Transaction</h3>

                @if($donation->blockchainTransaction)
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-neutral-500 mb-1">Status</p>
                            @php
                                $txStatusClass = match($donation->blockchainTransaction->status->value ?? $donation->blockchainTransaction->status) {
                                    'successful' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                    default => 'bg-neutral-100 text-neutral-800'
                                };
                            @endphp
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $txStatusClass }}">
                                {{ ucfirst($donation->blockchainTransaction->status->value ?? $donation->blockchainTransaction->status) }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm text-neutral-500 mb-1">Transaction Hash</p>
                            <p class="text-xs font-mono text-neutral-900 break-all">{{ $donation->blockchainTransaction->tx_hash }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-neutral-500 mb-1">From</p>
                            <p class="text-xs font-mono text-neutral-900 break-all">{{ $donation->blockchainTransaction->from_address }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-neutral-500 mb-1">To (Escrow)</p>
                            <p class="text-xs font-mono text-neutral-900 break-all">{{ $donation->blockchainTransaction->to_address }}</p>
                        </div>

                        @if(str_starts_with($donation->blockchainTransaction->tx_hash, 'pending_'))
                            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-xl">
                                <p class="text-xs text-yellow-800">Transaction is pending confirmation. Please check back later.</p>
                            </div>
                        @else
                            <a href="https://stellar.expert/explorer/testnet/tx/{{ $donation->blockchainTransaction->tx_hash }}" target="_blank" class="block w-full bg-primary hover:bg-primary-hover text-white text-center py-2.5 rounded-full text-sm font-medium transition-all">
                                View on Stellar Expert ↗
                            </a>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-neutral-500">No blockchain transaction recorded yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection