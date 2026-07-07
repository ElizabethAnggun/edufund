@extends('layouts.student')

@section('title', 'Dashboard')
@section('page-title', 'Student Dashboard')

@section('content')
    @php
        $user = auth()->user();
        $profile = $user->studentProfile;
        $profileComplete = $profile && $profile->nisn && $profile->phone && $profile->address;
        $fundingRequests = $profile ? $profile->fundingRequests : collect();
        $draftCount = $fundingRequests->where('status', \App\Enums\FundingRequestStatus::DRAFT)->count();
        $submittedCount = $fundingRequests->where('status', \App\Enums\FundingRequestStatus::PENDING_SCHOOL_APPROVAL)->count();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-surface p-6 rounded-xl border border-neutral-200 card-enhanced">
            <h3 class="text-sm font-medium text-neutral-500 mb-2">Profile</h3>
            <p class="text-2xl font-bold {{ $profileComplete ? 'text-green-600' : 'text-primary' }}">
                {{ $profileComplete ? 'Complete' : 'Incomplete' }}
            </p>
            <a href="{{ route('student.profile') }}" class="text-primary text-sm font-medium hover:text-primary-hover transition-colors mt-2 inline-block">Update Profile</a>
        </div>
        <div class="bg-surface p-6 rounded-xl border border-neutral-200 card-enhanced">
            <h3 class="text-sm font-medium text-neutral-500 mb-2">Draft Requests</h3>
            <p class="text-2xl font-bold text-primary">{{ $draftCount }}</p>
        </div>
        <div class="bg-surface p-6 rounded-xl border border-neutral-200 card-enhanced">
            <h3 class="text-sm font-medium text-neutral-500 mb-2">Submitted</h3>
            <p class="text-2xl font-bold text-primary">{{ $submittedCount }}</p>
        </div>
        <div class="bg-surface p-6 rounded-xl border border-neutral-200 card-enhanced">
            <h3 class="text-sm font-medium text-neutral-500 mb-2">Wallet</h3>
            <p class="text-2xl font-bold {{ $user->wallet_address ? 'text-green-600' : 'text-neutral-500' }}">
                {{ $user->wallet_address ? 'Connected' : 'Not Connected' }}
            </p>
        </div>
    </div>

    <div class="bg-surface p-6 rounded-xl border border-neutral-200">
        <h3 class="text-lg font-semibold text-neutral-900 mb-4">Recent Funding Requests</h3>
        @if($fundingRequests->count() > 0)
            <div class="space-y-3">
                @foreach($fundingRequests->take(5) as $request)
                    <div class="flex items-center justify-between p-4 border border-neutral-100 rounded-lg hover:bg-primary-soft transition-colors">
                        <div>
                            <h4 class="font-medium text-neutral-900">{{ $request->title }}</h4>
                            <p class="text-sm text-neutral-500 mt-1">
                                {{ number_format($request->total_amount, 2) }} {{ $request->currency }}
                                <span class="mx-2">&middot;</span>
                                <span class="px-2 py-0.5 text-xs rounded-full {{ $request->status === \App\Enums\FundingRequestStatus::DRAFT ? 'bg-neutral-100 text-neutral-700' : 'bg-primary-soft text-primary' }}">
                                    {{ ucwords(str_replace('_', ' ', $request->status->value)) }}
                                </span>
                            </p>
                        </div>
                        <a href="{{ route('student.funding-requests.show', $request) }}" class="text-primary text-sm font-medium hover:text-primary-hover transition-colors">View</a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-neutral-600">No funding requests yet. <a href="{{ route('student.funding-requests.create') }}" class="text-primary font-medium hover:text-primary-hover transition-colors">Create your first request</a>.</p>
        @endif
    </div>
@endsection

