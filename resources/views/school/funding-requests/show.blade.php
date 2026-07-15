@extends('layouts.school')

@section('title', 'Funding Request Details')
@section('page-title', 'Funding Request Details')

@section('content')
    <div class="mb-6">
        <a href="{{ route('school.funding-requests.index') }}" class="text-primary hover:text-primary-hover font-medium text-sm">
            ← Back to Funding Requests
        </a>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-neutral-900 mb-2">{{ $fundingRequest->title }}</h2>
                @php
                    $statusClass = match($fundingRequest->status) {
                        \App\Enums\FundingRequestStatus::PENDING_SCHOOL_APPROVAL => 'bg-yellow-100 text-yellow-800',
                        \App\Enums\FundingRequestStatus::APPROVED => 'bg-green-100 text-green-800',
                        \App\Enums\FundingRequestStatus::REJECTED => 'bg-red-100 text-red-800',
                        default => 'bg-neutral-100 text-neutral-800'
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                    {{ ucwords(str_replace('_', ' ', $fundingRequest->status->value)) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-background p-5 rounded-xl">
                <p class="text-sm text-neutral-500 mb-1">Student</p>
                <p class="text-lg font-semibold text-neutral-900">{{ $fundingRequest->studentProfile->user->name ?? 'Unknown' }}</p>
            </div>
            <div class="bg-background p-5 rounded-xl">
                <p class="text-sm text-neutral-500 mb-1">Amount Requested</p>
                <p class="text-2xl font-bold text-primary">${{ number_format($fundingRequest->total_amount, 2) }}</p>
            </div>
            <div class="bg-background p-5 rounded-xl">
                <p class="text-sm text-neutral-500 mb-1">Deadline</p>
                <p class="text-lg font-semibold text-neutral-900">{{ $fundingRequest->deadline->format('M d, Y') }}</p>
            </div>
        </div>

        <div class="mb-8">
            <h3 class="text-lg font-semibold text-neutral-900 mb-3">Description</h3>
            <p class="text-neutral-600 leading-relaxed">{{ $fundingRequest->description }}</p>
        </div>

        @if(in_array($fundingRequest->status, [\App\Enums\FundingRequestStatus::APPROVED, \App\Enums\FundingRequestStatus::ACTIVE, \App\Enums\FundingRequestStatus::COMPLETED]))
            <div class="mb-8">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold text-neutral-900">Milestones & Disbursements</h3>
                    <a href="{{ route('school.disbursements.create', $fundingRequest) }}" class="px-4 py-2 bg-primary hover:bg-primary-hover text-white text-sm font-medium rounded-full transition-all btn-press">
                        Release Funds
                    </a>
                </div>

                @if($fundingRequest->milestones->isEmpty())
                    <p class="text-neutral-500 text-sm">No milestones yet for this request.</p>
                @else
                    <div class="space-y-3">
                        @foreach($fundingRequest->milestones as $milestone)
                            <div class="bg-background p-4 rounded-xl flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-neutral-900">{{ $milestone->title }}</p>
                                    <p class="text-sm text-neutral-500">
                                        ${{ number_format($milestone->amount, 2) }}
                                        &middot; {{ ucwords(str_replace('_', ' ', $milestone->status->value)) }}
                                    </p>
                                </div>
                                @if($milestone->disbursement)
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Disbursed ${{ number_format($milestone->disbursement->amount, 2) }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                        Not yet disbursed
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        @if($fundingRequest->status === \App\Enums\FundingRequestStatus::PENDING_SCHOOL_APPROVAL)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-background p-5 rounded-xl">
                    <form action="{{ route('school.funding-requests.approve', $fundingRequest) }}" method="POST">
                        @csrf
                        <h3 class="text-lg font-semibold text-neutral-900 mb-3">Approve Request</h3>
                        <button type="submit" class="w-full px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-full transition-all btn-press" onclick="return confirm('Approve this funding request?')">
                            Approve Request
                        </button>
                    </form>
                </div>
                <div class="bg-background p-5 rounded-xl">
                    <form action="{{ route('school.funding-requests.reject', $fundingRequest) }}" method="POST">
                        @csrf
                        <h3 class="text-lg font-semibold text-neutral-900 mb-3">Reject Request</h3>
                        <div class="mb-4">
                            <label class="block text-neutral-700 font-medium mb-2 text-sm">Rejection Reason</label>
                            <textarea name="rejection_reason" rows="3" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="Enter rejection reason (minimum 10 characters)" required>{{ old('rejection_reason') }}</textarea>
                        </div>
                        <button type="submit" class="w-full px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-full transition-all btn-press" onclick="return confirm('Reject this funding request?')">
                            Reject Request
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection