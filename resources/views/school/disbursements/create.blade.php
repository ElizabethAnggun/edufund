@extends('layouts.school')

@section('title', 'Release Funds')
@section('page-title', 'Release Funds to Student')

@section('content')
    <div class="mb-6">
        <a href="{{ route('school.funding-requests.show', $fundingRequest) }}" class="text-primary hover:text-primary-hover font-medium text-sm">
            ← Back to Funding Request
        </a>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Release Milestone Funds</h2>
        <p class="text-neutral-600 mb-6">
            Disburse funds from the school wallet to
            <span class="font-semibold text-neutral-900">{{ $fundingRequest->studentProfile->user->name ?? 'the student' }}</span>
            for a completed milestone.
        </p>

        @if($fundingRequest->milestones->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
                This funding request has no milestones yet. Milestones are created when the campaign is active.
            </div>
        @else
            <div class="space-y-4">
                @foreach($fundingRequest->milestones as $milestone)
                    <div class="bg-background p-5 rounded-xl flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-neutral-900">{{ $milestone->title }}</p>
                            <p class="text-sm text-neutral-500">{{ $milestone->description }}</p>
                            <p class="text-sm text-neutral-600 mt-1">
                                Amount: <span class="font-semibold text-primary">${{ number_format($milestone->amount, 2) }}</span>
                                &middot; Status: {{ ucwords(str_replace('_', ' ', $milestone->status->value)) }}
                            </p>
                        </div>
                        <form action="{{ route('school.disbursements.store', ['fundingRequest' => $fundingRequest, 'milestone' => $milestone]) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-hover text-white font-medium rounded-full transition-all btn-press" onclick="return confirm('Release ${{ number_format($milestone->amount, 2) }} to the student?')">
                                Release
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
