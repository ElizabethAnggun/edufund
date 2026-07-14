@extends('layouts.student')

@section('title', 'Milestone Details')
@section('page-title', 'Milestone Details')

@section('content')
    <div class="mb-6">
        <a href="{{ route('student.milestones.index') }}" class="text-primary hover:text-primary-hover font-medium text-sm">
            ← Back to Milestones
        </a>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-neutral-900 mb-2">{{ $milestone->title }}</h2>
                @php
                    $statusClass = match($milestone->status) {
                        \App\Enums\MilestoneStatus::PENDING => 'bg-yellow-100 text-yellow-800',
                        \App\Enums\MilestoneStatus::SUBMITTED => 'bg-blue-100 text-blue-800',
                        \App\Enums\MilestoneStatus::VERIFIED => 'bg-green-100 text-green-800',
                        \App\Enums\MilestoneStatus::REJECTED => 'bg-red-100 text-red-800',
                        default => 'bg-neutral-100 text-neutral-800'
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                    {{ ucwords(str_replace('_', ' ', $milestone->status->value)) }}
                </span>
            </div>
            @if($milestone->status === \App\Enums\MilestoneStatus::PENDING)
                <form action="{{ route('student.milestones.submit', $milestone) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-full transition-all btn-press" onclick="return confirm('Submit this milestone for verification?')">
                        Submit for Verification
                    </button>
                </form>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-background p-5 rounded-xl">
                <p class="text-sm text-neutral-500 mb-1">Amount</p>
                <p class="text-2xl font-bold text-primary">${{ number_format($milestone->amount, 2) }}</p>
            </div>
            <div class="bg-background p-5 rounded-xl">
                <p class="text-sm text-neutral-500 mb-1">Due Date</p>
                <p class="text-2xl font-bold text-neutral-900">{{ $milestone->due_date->format('M d, Y') }}</p>
            </div>
            <div class="bg-background p-5 rounded-xl">
                <p class="text-sm text-neutral-500 mb-1">Funding Request</p>
                <p class="text-lg font-semibold text-neutral-900">{{ $milestone->fundingRequest->title ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="mb-8">
            <h3 class="text-lg font-semibold text-neutral-900 mb-3">Description</h3>
            <p class="text-neutral-600 leading-relaxed">{{ $milestone->description }}</p>
        </div>

        @if($milestone->verification_notes)
            <div class="mb-8 p-6 bg-neutral-50 rounded-xl border border-neutral-200">
                <h3 class="text-lg font-semibold text-neutral-900 mb-3">Verification Notes</h3>
                <p class="text-neutral-600 leading-relaxed">{{ $milestone->verification_notes }}</p>
            </div>
        @endif

        <div class="flex gap-4">
            <a href="{{ route('student.milestones.index') }}" class="px-6 py-2.5 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 font-medium rounded-full transition-all">
                Back to List
            </a>
        </div>
    </div>
@endsection