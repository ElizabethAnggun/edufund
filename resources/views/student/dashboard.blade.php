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
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Profile Completion</h3>
            <p class="text-2xl font-bold {{ $profileComplete ? 'text-green-600' : 'text-yellow-600' }}">
                {{ $profileComplete ? 'Complete' : 'Incomplete' }}
            </p>
            <a href="{{ route('student.profile') }}" class="text-blue-600 text-sm hover:underline mt-2 inline-block">Update Profile</a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Draft Requests</h3>
            <p class="text-2xl font-bold text-blue-600">{{ $draftCount }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Submitted Requests</h3>
            <p class="text-2xl font-bold text-purple-600">{{ $submittedCount }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Wallet Status</h3>
            <p class="text-2xl font-bold {{ $user->wallet_address ? 'text-green-600' : 'text-yellow-600' }}">
                {{ $user->wallet_address ? 'Connected' : 'Not Connected' }}
            </p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Funding Requests</h3>
        @if($fundingRequests->count() > 0)
            <div class="space-y-4">
                @foreach($fundingRequests->take(5) as $request)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-md">
                        <div>
                            <h4 class="font-medium text-gray-800">{{ $request->title }}</h4>
                            <p class="text-sm text-gray-600">
                                {{ number_format($request->total_amount, 2) }} {{ $request->currency }} • 
                                <span class="px-2 py-1 text-xs rounded-full {{ $request->status === \App\Enums\FundingRequestStatus::DRAFT ? 'bg-yellow-100 text-yellow-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ ucwords(str_replace('_', ' ', $request->status->value)) }}
                                </span>
                            </p>
                        </div>
                        <a href="{{ route('student.funding-requests.show', $request) }}" class="text-blue-600 hover:underline">View</a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No funding requests yet. <a href="{{ route('student.funding-requests.create') }}" class="text-blue-600 hover:underline">Create your first request</a>.</p>
        @endif
    </div>
@endsection

