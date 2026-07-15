@extends('layouts.donor')

@section('title', 'Browse Campaigns')
@section('page-title', 'Browse Campaigns')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Discover Campaigns</h2>
        <p class="text-neutral-500">Support students and make an impact through education</p>
    </div>

    <!-- Search and Filters -->
    <div class="bg-surface p-6 rounded-xl border border-neutral-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-neutral-700 text-sm font-medium mb-2">Search</label>
                <input type="text" placeholder="Search campaigns..." class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-neutral-700 text-sm font-medium mb-2">Category</label>
                <select class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all bg-white">
                    <option value="">All Categories</option>
                    <option value="tuition">Tuition</option>
                    <option value="research">Research</option>
                    <option value="equipment">Equipment</option>
                    <option value="living">Living Expenses</option>
                </select>
            </div>
            <div>
                <label class="block text-neutral-700 text-sm font-medium mb-2">Sort By</label>
                <select class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all bg-white">
                    <option value="newest">Newest</option>
                    <option value="popular">Most Popular</option>
                    <option value="urgent">Most Urgent</option>
                    <option value="ending">Ending Soon</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Campaigns Grid -->
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

                            <a href="{{ route('donor.campaigns.show', $campaign) }}" class="block w-full bg-primary hover:bg-primary-hover text-white text-center py-2.5 rounded-full text-sm font-medium transition-all btn-press">
                                Donate Now
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $campaigns->links() }}
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
@endsection