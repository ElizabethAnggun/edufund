@extends('layouts.donor')

@section('title', 'Campaign Details')
@section('page-title', 'Campaign Details')

@section('content')
    <div class="mb-6">
        <a href="{{ route('donor.campaigns.index') }}" class="text-primary hover:text-primary-hover font-medium text-sm">
            ← Back to Campaigns
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface p-8 rounded-xl border border-neutral-200">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-3xl font-bold text-neutral-900 mb-2">{{ $campaign->title }}</h2>
                        <p class="text-neutral-500">by {{ $campaign->fundingRequest->studentProfile->user->name ?? 'Student' }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-primary-soft text-primary">
                        {{ ucfirst($campaign->status->value ?? $campaign->status) }}
                    </span>
                </div>

                <div class="mb-6">
                    <p class="text-neutral-600 leading-relaxed">{{ $campaign->description }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-3">Funding Goal</h3>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-2xl font-bold text-primary">{{ number_format($campaign->current_amount, 7) }} XLM</span>
                        <span class="text-neutral-600">of {{ number_format($campaign->goal_amount, 7) }} XLM</span>
                    </div>
                    <div class="w-full bg-neutral-200 rounded-full h-3 mb-2">
                        <div class="bg-gradient-to-r from-primary to-primary-hover h-3 rounded-full" style="width: {{ $campaign->goal_amount > 0 ? min(($campaign->current_amount / $campaign->goal_amount) * 100, 100) : 0 }}%"></div>
                    </div>
                    <p class="text-sm text-neutral-600 text-center">
                        {{ $campaign->goal_amount > 0 ? round(($campaign->current_amount / $campaign->goal_amount) * 100, 1) : 0 }}% funded
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-background p-4 rounded-xl">
                        <p class="text-sm text-neutral-500 mb-1">Supporters</p>
                        <p class="text-xl font-bold text-neutral-900">{{ $campaign->donations->count() }}</p>
                    </div>
                    <div class="bg-background p-4 rounded-xl">
                        <p class="text-sm text-neutral-500 mb-1">Days Left</p>
                        <p class="text-xl font-bold text-neutral-900">{{ $campaign->ends_at ? max(0, now()->diffInDays($campaign->ends_at)) : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Student Information -->
            <div class="bg-surface p-8 rounded-xl border border-neutral-200">
                <h3 class="text-xl font-semibold text-neutral-900 mb-4">About the Student</h3>
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary to-primary-active flex items-center justify-center text-white font-bold text-xl">
                        {{ substr($campaign->fundingRequest->studentProfile->user->name ?? 'S', 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-neutral-900 mb-1">{{ $campaign->fundingRequest->studentProfile->user->name ?? 'Student' }}</h4>
                        <p class="text-sm text-neutral-500 mb-2">{{ $campaign->fundingRequest->studentProfile->user->email ?? '' }}</p>
                        <p class="text-sm text-neutral-600">{{ $campaign->fundingRequest->studentProfile->bio ?? 'No bio available' }}</p>
                    </div>
                </div>
            </div>

            <!-- Milestones -->
            @if($campaign->milestones->count() > 0)
                <div class="bg-surface p-8 rounded-xl border border-neutral-200">
                    <h3 class="text-xl font-semibold text-neutral-900 mb-4">Milestones</h3>
                    <div class="space-y-4">
                        @foreach($campaign->milestones as $milestone)
                            <div class="flex items-center justify-between p-4 bg-background rounded-xl">
                                <div>
                                    <p class="font-semibold text-neutral-900">{{ $milestone->title }}</p>
                                    <p class="text-sm text-neutral-500">{{ number_format($milestone->amount, 7) }} XLM</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($milestone->status->value === 'completed') bg-green-100 text-green-800
                                    @elseif($milestone->status->value === 'approved') bg-blue-100 text-blue-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($milestone->status->value) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar - Donation Form -->
        <div class="lg:col-span-1">
            <div class="bg-surface p-6 rounded-xl border border-neutral-200 sticky top-6">
                <h3 class="text-xl font-semibold text-neutral-900 mb-4">Make a Donation</h3>

                <a href="{{ route('donor.campaigns.donate', $campaign) }}" class="w-full bg-gradient-to-r from-primary to-primary-hover hover:from-primary-hover hover:to-primary-active text-white font-semibold py-3 px-4 rounded-full transition-all btn-press shadow-lg hover:shadow-xl text-center block mb-3">
                    Donate Now
                </a>

                @if($isSaved)
                    <form action="{{ route('donor.campaigns.unsave', $campaign) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-neutral-100 hover:bg-neutral-200 text-neutral-700 py-2.5 rounded-full text-sm font-medium transition-all">
                            Remove from Saved
                        </button>
                    </form>
                @else
                    <form action="{{ route('donor.campaigns.save', $campaign) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-neutral-100 hover:bg-neutral-200 text-neutral-700 py-2.5 rounded-full text-sm font-medium transition-all">
                            Save Campaign
                        </button>
                    </form>
                @endif

                <div class="mt-6 p-4 bg-primary-soft rounded-xl">
                    <p class="text-sm text-neutral-700">
                        <svg class="w-5 h-5 inline-block mr-1 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.053-.382-3.016z"/>
                        </svg>
                        <strong>Secure & Transparent</strong><br>
                        Your donation is recorded on the blockchain
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection