@extends('layouts.student')

@section('title', 'Milestones')
@section('page-title', 'My Milestones')

@section('content')
    <div class="mb-6">
        <a href="{{ route('student.funding-requests.index') }}" class="text-primary hover:text-primary-hover font-medium text-sm">
            ← Back to Funding Requests
        </a>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        @if($milestones->count() > 0)
            <div class="space-y-6">
                @foreach($milestones as $milestone)
                    <div class="p-6 bg-background rounded-xl border border-neutral-200 hover:border-primary-soft transition-colors">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h4 class="text-lg font-semibold text-neutral-900">{{ $milestone->title }}</h4>
                                    @php
                                        $statusClass = match($milestone->status) {
                                            \App\Enums\MilestoneStatus::PENDING => 'bg-yellow-100 text-yellow-800',
                                            \App\Enums\MilestoneStatus::SUBMITTED => 'bg-blue-100 text-blue-800',
                                            \App\Enums\MilestoneStatus::VERIFIED => 'bg-green-100 text-green-800',
                                            \App\Enums\MilestoneStatus::REJECTED => 'bg-red-100 text-red-800',
                                            default => 'bg-neutral-100 text-neutral-800'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucwords(str_replace('_', ' ', $milestone->status->value)) }}
                                    </span>
                                </div>
                                <p class="text-sm text-neutral-500 mb-3">{{ $milestone->description }}</p>
                                <div class="flex items-center gap-6 text-sm text-neutral-600">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        ${{ number_format($milestone->amount, 2) }}
                                    </span>
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Due: {{ $milestone->due_date->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('student.milestones.show', $milestone) }}" class="px-4 py-2 bg-primary hover:bg-primary-hover text-white text-sm font-medium rounded-full transition-all btn-press">
                                    View Details
                                </a>
                                @if($milestone->status === \App\Enums\MilestoneStatus::PENDING)
                                    <form action="{{ route('student.milestones.submit', $milestone) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-full transition-all" onclick="return confirm('Submit this milestone for verification?')">
                                            Submit
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <p class="text-neutral-500">No milestones yet</p>
                <p class="text-sm text-neutral-400 mt-2">Milestones will appear here once created for your funding requests</p>
            </div>
        @endif
    </div>
@endsection