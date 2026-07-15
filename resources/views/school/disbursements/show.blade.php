@extends('layouts.school')

@section('title', 'Disbursement Detail')
@section('page-title', 'Disbursement Detail')

@section('content')
    <div class="mb-6">
        <a href="{{ route('school.disbursements.index') }}" class="text-primary hover:text-primary-hover font-medium text-sm">
            ← Back to Disbursements
        </a>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-neutral-900 mb-2">Disbursement #{{ $disbursement->id }}</h2>
                @php
                    $statusClass = match($disbursement->status) {
                        \App\Enums\DisbursementStatus::COMPLETED => 'bg-green-100 text-green-800',
                        \App\Enums\DisbursementStatus::PENDING => 'bg-yellow-100 text-yellow-800',
                        \App\Enums\DisbursementStatus::FAILED => 'bg-red-100 text-red-800',
                        default => 'bg-neutral-100 text-neutral-800'
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                    {{ ucwords($disbursement->status->value) }}
                </span>
            </div>
            <p class="text-3xl font-bold text-primary">${{ number_format($disbursement->amount, 2) }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-background p-5 rounded-xl">
                <p class="text-sm text-neutral-500 mb-1">Student</p>
                <p class="text-lg font-semibold text-neutral-900">{{ $disbursement->studentProfile->user->name ?? 'Unknown' }}</p>
            </div>
            <div class="bg-background p-5 rounded-xl">
                <p class="text-sm text-neutral-500 mb-1">Milestone</p>
                <p class="text-lg font-semibold text-neutral-900">{{ $disbursement->milestone->title ?? '-' }}</p>
            </div>
            <div class="bg-background p-5 rounded-xl">
                <p class="text-sm text-neutral-500 mb-1">From (School Wallet)</p>
                <p class="text-sm font-mono text-neutral-700 break-all">{{ $disbursement->from_address ?? 'Not set' }}</p>
            </div>
            <div class="bg-background p-5 rounded-xl">
                <p class="text-sm text-neutral-500 mb-1">To (Student Wallet)</p>
                <p class="text-sm font-mono text-neutral-700 break-all">{{ $disbursement->to_address ?? 'Not set' }}</p>
            </div>
            <div class="bg-background p-5 rounded-xl">
                <p class="text-sm text-neutral-500 mb-1">Transaction Hash</p>
                <p class="text-sm font-mono text-neutral-700 break-all">{{ $disbursement->tx_hash ?? '-' }}</p>
            </div>
            <div class="bg-background p-5 rounded-xl">
                <p class="text-sm text-neutral-500 mb-1">Released At</p>
                <p class="text-lg font-semibold text-neutral-900">{{ $disbursement->released_at ? $disbursement->released_at->format('M d, Y H:i') : '-' }}</p>
            </div>
        </div>

        @if($disbursement->notes)
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-neutral-900 mb-3">Notes</h3>
                <p class="text-neutral-600 leading-relaxed">{{ $disbursement->notes }}</p>
            </div>
        @endif

        @if($disbursement->blockchainTransaction)
            <div class="bg-background p-5 rounded-xl">
                <h3 class="text-lg font-semibold text-neutral-900 mb-3">Blockchain Transaction</h3>
                <p class="text-sm text-neutral-600">Type: {{ $disbursement->blockchainTransaction->type->value }}</p>
                <p class="text-sm text-neutral-600">Status: {{ $disbursement->blockchainTransaction->status->value }}</p>
                <p class="text-sm text-neutral-600">Network: {{ $disbursement->blockchainTransaction->network->value }}</p>
                <p class="text-sm text-neutral-600 break-all">Hash: {{ $disbursement->blockchainTransaction->tx_hash }}</p>
            </div>
        @endif
    </div>
@endsection
