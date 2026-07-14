@extends('layouts.donor')

@section('title', 'Donation History')
@section('page-title', 'Donation History')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Your Donations</h2>
        <p class="text-neutral-500">Track your contributions and impact</p>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        @if(isset($donations) && $donations->count() > 0)
            <div class="space-y-4">
                @foreach($donations as $donation)
                    <div class="p-6 bg-background rounded-xl border border-neutral-200 hover:border-primary-soft transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h4 class="text-lg font-semibold text-neutral-900">{{ $donation->campaign->title ?? 'Unknown Campaign' }}</h4>
                                    @php
                                        $statusClass = match($donation->status) {
                                            'completed' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'failed' => 'bg-red-100 text-red-800',
                                            default => 'bg-neutral-100 text-neutral-800'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-neutral-500 mb-2">
                                    @if($donation->campaign && $donation->campaign->fundingRequest && $donation->campaign->fundingRequest->studentProfile)
                                        To: {{ $donation->campaign->fundingRequest->studentProfile->user->name ?? 'Student' }}
                                    @endif
                                </p>
                                @if($donation->message)
                                    <p class="text-sm text-neutral-600 italic mb-2">"{{ $donation->message }}"</p>
                                @endif
                                <p class="text-xs text-neutral-400">{{ $donation->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-primary">${{ number_format($donation->amount, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $donations->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <p class="text-neutral-500 text-lg mb-2">No donations yet</p>
                <p class="text-sm text-neutral-400 mb-4">Start making a difference by supporting students</p>
                <a href="{{ route('donor.campaigns.index') }}" class="inline-block bg-primary hover:bg-primary-hover text-white px-6 py-2.5 rounded-full text-sm font-medium transition-all btn-press">
                    Browse Campaigns
                </a>
            </div>
        @endif
    </div>
@endsection