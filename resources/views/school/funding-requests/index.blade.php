@extends('layouts.school')

@section('title', 'Funding Requests')
@section('page-title', 'Funding Requests')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Funding Requests</h2>
        <p class="text-neutral-500">Review and manage student funding requests</p>
    </div>

    <!-- Filters -->
    <div class="bg-surface p-6 rounded-xl border border-neutral-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-neutral-700 text-sm font-medium mb-2">Status</label>
                <select class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all bg-white">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div>
                <label class="block text-neutral-700 text-sm font-medium mb-2">Student</label>
                <input type="text" placeholder="Search by student name..." class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-neutral-700 text-sm font-medium mb-2">Date Range</label>
                <input type="date" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
            </div>
        </div>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        @if(isset($fundingRequests) && $fundingRequests->count() > 0)
            <div class="space-y-4">
                @foreach($fundingRequests as $request)
                    <div class="p-6 bg-background rounded-xl border border-neutral-200 hover:border-primary-soft transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h4 class="text-lg font-semibold text-neutral-900">{{ $request->title }}</h4>
                                    @php
                                        $statusClass = match($request->status) {
                                            \App\Enums\FundingRequestStatus::PENDING_SCHOOL_APPROVAL => 'bg-yellow-100 text-yellow-800',
                                            \App\Enums\FundingRequestStatus::APPROVED => 'bg-green-100 text-green-800',
                                            \App\Enums\FundingRequestStatus::REJECTED => 'bg-red-100 text-red-800',
                                            default => 'bg-neutral-100 text-neutral-800'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucwords(str_replace('_', ' ', $request->status->value)) }}
                                    </span>
                                </div>
                                <p class="text-sm text-neutral-500 mb-2">By {{ $request->studentProfile->user->name ?? 'Unknown Student' }}</p>
                                <p class="text-sm text-neutral-600 mb-3 line-clamp-2">{{ $request->description }}</p>
                                <div class="flex items-center gap-6 text-sm text-neutral-600">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        ${{ number_format($request->total_amount, 2) }}
                                    </span>
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Deadline: {{ $request->deadline->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('school.funding-requests.show', $request) }}" class="px-4 py-2 bg-primary hover:bg-primary-hover text-white text-sm font-medium rounded-full transition-all btn-press">
                                    Review
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $fundingRequests->links() }}
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
@endsection