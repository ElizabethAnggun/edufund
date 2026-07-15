@extends('layouts.admin')

@section('title', 'Transaction Monitoring')
@section('page-title', 'Transaction Monitoring')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Blockchain Transaction Monitoring</h2>
        <p class="text-neutral-500">Track and verify all Stellar blockchain transactions</p>
    </div>

    <div class="bg-surface rounded-xl border border-neutral-200">
        @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-medium text-neutral-500 uppercase tracking-wider border-b border-neutral-200">
                            <th class="px-6 py-4">Transaction Hash</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">Amount</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Network</th>
                            <th class="px-6 py-4">From/To</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($transactions as $tx)
                            <tr class="hover:bg-neutral-50 transition-colors {{ $tx->status->value === 'pending' ? 'bg-yellow-50' : ($tx->status->value === 'failed' ? 'bg-red-50' : '') }}">
                                <td class="px-6 py-4">
                                    <div class="text-xs font-mono text-neutral-900">{{ str_replace('pending_', 'PND-', substr($tx->tx_hash, 0, 20)) }}{{ strlen($tx->tx_hash) > 20 ? '...' : '' }}</div>
                                    <div class="text-xs text-neutral-500 mt-1">{{ $tx->tx_hash }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                                        {{ $tx->type->value ?? $tx->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-neutral-900">{{ number_format($tx->amount, 2) }} {{ $tx->currency }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = match($tx->status->value ?? $tx->status) {
                                            'successful' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'failed' => 'bg-red-100 text-red-800',
                                            default => 'bg-neutral-100 text-neutral-800'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($tx->status->value ?? $tx->status) }}
                                    </span>
                                    @if($tx->status->value === 'pending')
                                        <div class="text-xs text-yellow-700 mt-1">Awaiting confirmation</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-mono text-neutral-600">{{ substr($tx->from_address, 0, 10) }}...{{ substr($tx->from_address, -10) }}</div>
                                    <div class="text-xs font-mono text-neutral-600 mt-1">→ {{ substr($tx->to_address, 0, 10) }}...{{ substr($tx->to_address, -10) }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-500">{{ $tx->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4">
                                    @if($tx->status->value === 'pending')
                                        <form action="{{ route('admin.transactions.retry', $tx) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-primary hover:text-primary-hover font-medium text-sm px-3 py-1 rounded-lg hover:bg-primary-soft transition-all">
                                                Retry
                                            </button>
                                        </form>
                                    @else
                                        <a href="#" class="text-neutral-400 cursor-not-allowed font-medium text-sm px-3 py-1 rounded-lg">
                                            No Action
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-neutral-200">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H9zm0 0V3m0 2v10"/>
                </svg>
                <p class="text-neutral-500">No transactions found</p>
            </div>
        @endif
    </div>
@endsection