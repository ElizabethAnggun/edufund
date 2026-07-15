@extends('layouts.donor')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
    <div class="mb-6">
        <a href="{{ route('donor.dashboard') }}" class="text-primary hover:text-primary-hover font-medium text-sm">
            ← Back to Dashboard
        </a>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        @if(isset($notifications) && $notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="p-5 bg-background rounded-xl border border-neutral-200 hover:border-primary-soft transition-colors {{ !$notification->is_read ? 'border-l-4 border-l-primary' : '' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <h4 class="font-semibold text-neutral-900">{{ $notification->title }}</h4>
                                    @if(!$notification->is_read)
                                        <span class="w-2 h-2 bg-primary rounded-full"></span>
                                    @endif
                                </div>
                                <p class="text-sm text-neutral-600 mb-2">{{ $notification->message }}</p>
                                <p class="text-xs text-neutral-400">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-20 h-20 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p class="text-neutral-500 text-lg mb-2">No notifications yet</p>
                <p class="text-sm text-neutral-400">You'll receive updates about your donations</p>
            </div>
        @endif
    </div>
@endsection