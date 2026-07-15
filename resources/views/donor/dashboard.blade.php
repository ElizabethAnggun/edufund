@extends('layouts.donor')

@section('title', 'Dashboard')
@section('page-title', 'Donor Dashboard')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Welcome back, {{ auth()->user()->name }}</h2>
        <p class="text-neutral-500">Make a difference through education funding</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-surface p-6 rounded-xl border border-neutral-200 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-primary-soft rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-neutral-500 mb-1">Total Donations</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $stats['total_donations'] ?? 0 }}</p>
        </div>

        <div class="bg-surface p-6 rounded-xl border border-neutral-200 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-neutral-500 mb-1">Campaigns Supported</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $stats['campaigns_supported'] ?? 0 }}</p>
        </div>

        <div class="bg-surface p-6 rounded-xl border border-neutral-200 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-neutral-500 mb-1">Total Donated</p>
            <p class="text-3xl font-bold text-neutral-900">${{ number_format($totalDonationAmount ?? 0, 2) }}</p>
        </div>

        <div class="bg-surface p-6 rounded-xl border border-neutral-200 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-neutral-500 mb-1">Impact Score</p>
            <p class="text-3xl font-bold text-neutral-900">{{ min(($stats['total_donations'] ?? 0) * 10, 100) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Donations -->
        <div class="lg:col-span-2 bg-surface rounded-xl border border-neutral-200">
            <div class="p-6 border-b border-neutral-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-neutral-900">Recent Donations</h3>
                    <a href="{{ route('donor.donations.index') }}" class="text-sm text-primary hover:text-primary-hover font-medium">View All</a>
                </div>
            </div>
            <div class="p-6">
                @if($recentDonations->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentDonations as $donation)
                            <div class="flex items-center justify-between p-4 bg-background rounded-lg hover:bg-neutral-50 transition-colors">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-neutral-900 mb-1">{{ $donation->campaign->title ?? 'Unknown Campaign' }}</h4>
                                    <p class="text-sm text-neutral-500">
                                        @if($donation->campaign && $donation->campaign->fundingRequest && $donation->campaign->fundingRequest->studentProfile)
                                            {{ $donation->campaign->fundingRequest->studentProfile->user->name ?? 'Unknown Student' }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-neutral-400 mt-1">{{ $donation->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-primary mb-1">${{ number_format($donation->amount, 2) }}</p>
                                    @php
                                        $statusClass = match($donation->status) {
                                            'completed' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'failed' => 'bg-red-100 text-red-800',
                                            default => 'bg-neutral-100 text-neutral-800'
                                        };
                                    @endphp
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <p class="text-neutral-500">No donations yet</p>
                        <a href="{{ route('donor.campaigns.index') }}" class="inline-block mt-4 bg-primary hover:bg-primary-hover text-white px-6 py-2 rounded-full text-sm font-medium transition-all btn-press">Browse Campaigns</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-surface rounded-xl border border-neutral-200">
            <div class="p-6 border-b border-neutral-200">
                <h3 class="text-lg font-semibold text-neutral-900">Recent Activities</h3>
            </div>
            <div class="p-6">
                @if($recentActivities->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                            <div class="flex gap-3">
                                <div class="w-2 h-2 bg-primary rounded-full mt-2 flex-shrink-0"></div>
                                <div class="flex-1">
                                    <p class="text-sm text-neutral-700">{{ $activity['message'] }}</p>
                                    <p class="text-xs text-neutral-400 mt-1">{{ \Carbon\Carbon::parse($activity['timestamp'])->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-neutral-500 text-center py-8">No recent activities</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recommended Campaigns -->
    <div class="bg-surface rounded-xl border border-neutral-200">
        <div class="p-6 border-b border-neutral-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-neutral-900">Recommended Campaigns</h3>
                <a href="{{ route('donor.campaigns.index') }}" class="text-sm text-primary hover:text-primary-hover font-medium">View All</a>
            </div>
        </div>
        <div class="p-6">
            @if($recommendedCampaigns->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recommendedCampaigns as $campaign)
                        <div class="bg-background rounded-xl border border-neutral-200 overflow-hidden hover-lift">
                            <div class="p-6">
                                <h4 class="font-semibold text-neutral-900 mb-2">{{ $campaign->title }}</h4>
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

                                <a href="{{ route('donor.campaigns.show', $campaign) }}" class="block w-full bg-primary hover:bg-primary-hover text-white text-center py-2.5 rounded-full text-sm font-medium transition-all btn-press">
                                    Donate Now
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <p class="text-neutral-500">No campaigns available at the moment</p>
                </div>
            @endif
        </div>
    </div>
@endsection