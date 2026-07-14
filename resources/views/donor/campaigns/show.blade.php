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
                        Active
                    </span>
                </div>

                <div class="mb-6">
                    <p class="text-neutral-600 leading-relaxed">{{ $campaign->description }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-3">Funding Goal</h3>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-2xl font-bold text-primary">${{ number_format($campaign->current_amount, 2) }}</span>
                        <span class="text-neutral-600">of ${{ number_format($campaign->goal_amount, 2) }}</span>
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
                        <p class="text-xl font-bold text-neutral-900">{{ $campaign->end_date ? max(0, now()->diffInDays($campaign->end_date)) : 'N/A' }}</p>
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
        </div>

        <!-- Sidebar - Donation Form -->
        <div class="lg:col-span-1">
            <div class="bg-surface p-6 rounded-xl border border-neutral-200 sticky top-6">
                <h3 class="text-xl font-semibold text-neutral-900 mb-4">Make a Donation</h3>
                
                <form action="{{ route('donor.donations.store', $campaign) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-neutral-700 text-sm font-medium mb-2">Donation Amount (USD)</label>
                        <input type="number" name="amount" step="0.01" min="1" required
                               class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                               placeholder="100.00">
                    </div>

                    <div class="mb-6">
                        <label class="block text-neutral-700 text-sm font-medium mb-2">Message (Optional)</label>
                        <textarea name="message" rows="3"
                                  class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none"
                                  placeholder="Write an encouraging message..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-primary to-primary-hover hover:from-primary-hover hover:to-primary-active text-white font-semibold py-3 px-4 rounded-full transition-all btn-press shadow-lg hover:shadow-xl">
                        Donate Now
                    </button>
                </form>

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