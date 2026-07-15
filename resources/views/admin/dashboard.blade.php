@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Welcome back, {{ auth()->user()->name }}</h2>
        <p class="text-neutral-500">Platform overview and management</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-surface p-6 rounded-xl border border-neutral-200 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-primary-soft rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-neutral-500 mb-1">Total Users</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $stats['total_users'] ?? 0 }}</p>
        </div>

        <div class="bg-surface p-6 rounded-xl border border-neutral-200 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-neutral-500 mb-1">Active Campaigns</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $stats['active_campaigns'] ?? 0 }}</p>
        </div>

        <div class="bg-surface p-6 rounded-xl border border-neutral-200 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-neutral-500 mb-1">Total Donations</p>
            <p class="text-3xl font-bold text-neutral-900">${{ number_format($stats['total_donations'] ?? 0, 2) }}</p>
        </div>

        <div class="bg-surface p-6 rounded-xl border border-neutral-200 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-neutral-500 mb-1">Institutions</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $stats['total_schools'] ?? 0 }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-surface rounded-xl border border-neutral-200">
            <div class="p-6 border-b border-neutral-200">
                <h3 class="text-lg font-semibold text-neutral-900">Recent Users</h3>
            </div>
            <div class="p-6">
                @if(isset($recentUsers) && $recentUsers->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentUsers as $user)
                            <div class="flex items-center justify-between p-4 bg-background rounded-lg hover:bg-neutral-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary-active flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-neutral-900 text-sm">{{ $user->name }}</p>
                                        <p class="text-xs text-neutral-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-medium bg-primary-soft text-primary">
                                        {{ ucfirst($user->getRoleNames()->first()) }}
                                    </span>
                                    <p class="text-xs text-neutral-400 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-neutral-500 text-center py-8">No users yet</p>
                @endif
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-surface rounded-xl border border-neutral-200">
            <div class="p-6 border-b border-neutral-200">
                <h3 class="text-lg font-semibold text-neutral-900">Platform Activities</h3>
            </div>
            <div class="p-6">
                @if(isset($recentActivities) && $recentActivities->count() > 0)
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
@endsection
