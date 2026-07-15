@extends('layouts.student')

@section('title', 'Transactions')
@section('page-title', 'Transaction History')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Transaction History</h2>
        <p class="text-neutral-500">View all your transactions</p>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        @if($transactions->count() > 0)
            <div class="space-y-4">
                @foreach($transactions as $transaction)
                    <div class="flex items-center justify-between p-4 bg-background rounded-xl">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-neutral-900">{{ $transaction['title'] }}</p>
                                <p class="text-sm text-neutral-500">{{ \Carbon\Carbon::parse($transaction['date'])->diffForHumans() }}</p>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-green-600">+${{ number_format($transaction['amount'], 2) }}</p>
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
