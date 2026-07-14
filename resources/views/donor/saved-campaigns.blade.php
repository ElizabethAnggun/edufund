@extends('layouts.donor')

@section('title', 'Saved Campaigns')
@section('page-title', 'Saved Campaigns')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Saved Campaigns</h2>
        <p class="text-neutral-500">Campaigns you're supporting</p>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        @if(isset($campaigns) && $campaigns->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($campaigns as $campaign)
                    <div class="bg-background rounded-xl border border-neutral-200 overflow-hidden hover-lift">
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-primary-active flex items-center justify-center text-white font-bold">
                                    {{ substr($campaign->fundingRequest->studentProfile->user->name ?? 'S', 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-neutral-900 text-sm">{{ $campaign->fundingRequest->studentProfile->user->name ?? 'Student' }}</h4>
                                    <p class="text-xs text-neutral-500">{{ $campaign->fundingRequest->school->name ?? 'School' }}</p>
                                </div>
                            </div>

                            <h3 class="font-bold text-neutral-900 mb-2">{{ $campaign->title }}</h3>
                            <p class="text-sm text-neutral-500 mb-4 line-clamp-2">{{ $campaign->description }}</p>

                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-neutral-600">Progress</span>
                                    <span class="text-sm font-semibold text-primary">
                                        {{ $campaign->goal_amount > 0 ? round(($campaign->current_amount / $campaign->goal_amount) * 100, 1) : 0 }}%
                                    </span>
                                </div>
                                <div class="w-full bg-neutral-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-primary to-primary-hover h-2 rounded-full" style="width: {{ $campaign->goal_amount > 0 ? min(($campaign->current_amount / $campaign->goal_amount) * 100, 100) : 0 }}%"></div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-xs text-neutral-500">Raised</p>
                                    <p class="text-lg font-bold text-primary">${{ number_format($campaign->current_amount, 2) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-neutral-500">Goal</p>
                                    <p class="text-sm font-semibold text-neutral-700">${{ number_format($campaign->goal_amount, 2) }}</p>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('donor.campaigns.show', $campaign) }}" class="flex-1 bg-primary hover:bg-primary-hover text-white text-center py-2.5 rounded-full text-sm font-medium transition-all btn-press">
                                    Donate
                                </a>
                                <form action="{{ route('donor.campaigns.unsave', $campaign) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-neutral-100 hover:bg-neutral-200 text-neutral-700 py-2.5 rounded-full text-sm font-medium transition-all">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <p class="text-neutral-500 text-lg mb-2">No saved campaigns</p>
                <p class="text-sm text-neutral-400 mb-4">Save campaigns you want to support</p>
                <a href="{{ route('donor.campaigns.index') }}" class="inline-block bg-primary hover:bg-primary-hover text-white px-6 py-2.5 rounded-full text-sm font-medium transition-all btn-press">
                    Browse Campaigns
                </a>
            </div>
        @endif
    </div>
@endsection