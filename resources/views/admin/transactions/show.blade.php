@extends('layouts.admin')

@section('title', 'Transaction Details')
@section('page-title', 'Transaction Details')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.transactions.index') }}" class="text-primary hover:text-primary-hover font-medium text-sm">
            ← Back to Transactions
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface p-8 rounded-xl border border-neutral-200">
                <h2 class="text-2xl font-bold text-neutral-900 mb-6">Transaction Information</h2>
                
                <div class="mb-6">
                    <p class="text-sm text-neutral-500 mb-1">Transaction Hash</p>
                    <p class="font-mono text-neutral-900 break-all">{{ $transaction->tx_hash }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-neutral-500 mb-1">Type</p>
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                            {{ $transaction->type->value ?? $transaction->type }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-500 mb-1">Status</p>
                        @php
                            $statusClass = match($transaction->status->value ?? $transaction->status) {
                                'successful' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'failed' => 'bg-red-100 text-red-800',
                                default => 'bg-neutral-100 text-neutral-800'
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                            {{ ucfirst($transaction->status->value ?? $transaction->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-500 mb-1">Amount</p>
                        <p class="font-semibold text-neutral-900">{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-500 mb-1">Network</p>
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $transaction->network->value ?? $transaction->network }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-500 mb-1">Created At</p>
                        <p class="font-semibold text-neutral-900">{{ $transaction->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    @if($transaction->confirmed_at)
                        <div>
                            <p class="text-sm text-neutral-500 mb-1">Confirmed At</p>
                            <p class="font-semibold text-neutral-900">{{ $transaction->confirmed_at->format('M d, Y H:i') }}</p>
                        </div>
                    @endif
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-3">Address Information</h3>
                    <div class="bg-background p-4 rounded-xl space-y-3">
                        <div>
                            <p class="text-sm text-neutral-500 mb-1">From Address</p>
                            <p class="font-mono text-xs text-neutral-900 break-all">{{ $transaction->from_address }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-neutral-500 mb-1">To Address</p>
                            <p class="font-mono text-xs text-neutral-900 break-all">{{ $transaction->to_address }}</p>
                        </div>
                    </div>
                </div>

                @if($transaction->error_message)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-3">Error Details</h3>
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                            <p class="text-sm text-red-800">{{ $transaction->error_message }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-surface p-6 rounded-xl border border-neutral-200 sticky top-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Transactionable</h3>
                @php
                    $model = $transaction->transactionable;
                    $modelName = $transaction->transactionable_type;
                @endphp
                @if($model)
                    <div class="bg-neutral-50 rounded-xl p-4">
                        <p class="text-sm text-neutral-500 mb-2">Related {{ $modelName }}</p>
                        <p class="font-semibold text-neutral-900 mb-2">{{ $modelName }} ID: {{ $transaction->transactionable_id }}</p>
                        
                        @if($modelName === 'App\Models\Donation' && method_exists($model, 'campaign'))
                            <div class="mt-3">
                                <p class="text-xs text-neutral-500">Campaign:</p>
                                <p class="text-sm font-medium text-neutral-900">{{ $model->campaign->title ?? 'N/A' }}</p>
                                <p class="text-xs text-neutral-500 mt-1">Amount: {{ number_format($model->amount, 2) }} {{ $model->currency ?? 'XLM' }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-neutral-500">No transactionable record found.</p>
                @endif

                @if($transaction->status->value === 'pending')
                    <div class="mt-6">
                        <form action="{{ route('admin.transactions.retry', $transaction) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white font-medium py-3 px-4 rounded-lg transition-all">
                                Retry Transaction
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection