@extends('layouts.school')

@section('title', 'Dashboard')
@section('page-title', 'School Dashboard')

@section('content')
    @if(!$school)
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-yellow-900 mb-2">School Profile Not Complete</h3>
            <p class="text-yellow-700">Please complete your school profile to access the dashboard.</p>
        </div>
    @else
        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-neutral-900 mb-2">Welcome back, {{ auth()->user()->name }}</h2>
            <p class="text-neutral-500">Here's what's happening with {{ $school->name }}</p>
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
                <p class="text-sm text-neutral-500 mb-1">Total Students</p>
                <p class="text-3xl font-bold text-neutral-900">{{ $stats['total_students'] ?? 0 }}</p>
            </div>

            <div class="bg-surface p-6 rounded-xl border border-neutral-200 hover-lift">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-neutral-500 mb-1">Pending Requests</p>
                <p class="text-3xl font-bold text-neutral-900">{{ $stats['pending_funding_requests'] ?? 0 }}</p>
            </div>

            <div class="bg-surface p-6 rounded-xl border border-neutral-200 hover-lift">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-neutral-500 mb-1">Approved Requests</p>
                <p class="text-3xl font-bold text-neutral-900">{{ $stats['approved_funding_requests'] ?? 0 }}</p>
            </div>

            <div class="bg-surface p-6 rounded-xl border border-neutral-200 hover-lift">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-neutral-500 mb-1">Rejected Requests</p>
                <p class="text-3xl font-bold text-neutral-900">{{ $stats['rejected_funding_requests'] ?? 0 }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Funding Requests -->
            <div class="lg:col-span-2 bg-surface rounded-xl border border-neutral-200">
                <div class="p-6 border-b border-neutral-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-neutral-900">Recent Funding Requests</h3>
                        <a href="{{ route('school.funding-requests.index') }}" class="text-sm text-primary hover:text-primary-hover font-medium">View All</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentFundingRequests->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentFundingRequests as $request)
                                <div class="flex items-center justify-between p-4 bg-background rounded-lg hover:bg-neutral-50 transition-colors">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-neutral-900 mb-1">{{ $request->title }}</h4>
                                        <p class="text-sm text-neutral-500">By {{ $request->studentProfile->user->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-neutral-400 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-primary mb-1">${{ number_format($request->total_amount, 2) }}</p>
                                        @php
                                            $statusClass = match($request->status) {
                                                \App\Enums\FundingRequestStatus::PENDING_SCHOOL_APPROVAL => 'bg-yellow-100 text-yellow-800',
                                                \App\Enums\FundingRequestStatus::APPROVED => 'bg-green-100 text-green-800',
                                                \App\Enums\FundingRequestStatus::REJECTED => 'bg-red-100 text-red-800',
                                                default => 'bg-neutral-100 text-neutral-800'
                                            };
                                        @endphp
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ $request->status->value }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-neutral-500">No funding requests yet</p>
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
    @endif
@endsection