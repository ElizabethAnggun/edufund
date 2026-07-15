@extends('layouts.student')

@section('title', 'Transactions')
@section('page-title', 'Transaction History')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Transaction History</h2>
        <p class="text-neutral-500">Donations received on your campaigns and fund releases from your school.</p>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        @if($transactions->count() > 0)
            <div class="space-y-4">
                @foreach($transactions as $transaction)
                    <div class="flex items-center justify-between p-4 bg-background rounded-xl">
                        <div class="flex items-center gap-4">
                            @if($transaction['type'] === 'donation')
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-neutral-900">{{ $transaction['title'] }}</p>
                                <p class="text-sm text-neutral-500">
                                    {{ ucfirst($transaction['type']) }}
                                    @if(!empty($transaction['counterparty']))
                                        &middot; {{ $transaction['counterparty'] }}
                                    @endif
                                    @if(!empty($transaction['tx_hash']))
                                        &middot; <span class="font-mono text-xs">{{ \Illuminate\Support\Str::limit($transaction['tx_hash'], 16) }}</span>
                                    @endif
                                </p>
                                <p class="text-xs text-neutral-400 mt-1">{{ \Carbon\Carbon::parse($transaction['timestamp'])->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-green-600">+${{ number_format($transaction['amount'], 2) }}</p>
                            @php
                                $statusClass = match($transaction['status']) {
                                    'completed', 'successful' => 'bg-green-100 text-green-800',
                                    'pending', 'processing' => 'bg-yellow-100 text-yellow-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                    default => 'bg-neutral-100 text-neutral-800'
                                };
                            @endphp
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ ucwords($transaction['status']) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-neutral-500">No transactions yet</p>
            </div>
        @endif
    </div>
@endsection
