@extends('layouts.school')

@section('title', 'Dashboard')
@section('page-title', 'School Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Menunggu Persetujuan</h3>
            <p class="text-2xl font-bold text-yellow-600">{{ $pendingCount }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Disetujui</h3>
            <p class="text-2xl font-bold text-green-600">{{ $approvedCount }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Ditolak</h3>
            <p class="text-2xl font-bold text-red-600">{{ $rejectedCount }}</p>
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
                                Oleh: {{ $request->studentProfile->user->name }} • 
                                Rp {{ number_format($request->total_amount, 2) }} • 
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $request->status == \App\Enums\FundingRequestStatus::PENDING_SCHOOL_APPROVAL ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $request->status == \App\Enums\FundingRequestStatus::APPROVED ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $request->status == \App\Enums\FundingRequestStatus::REJECTED ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ ucwords(str_replace('_', ' ', $request->status->value)) }}
                                </span>
                            </p>
                        </div>
                        <a href="{{ route('school.funding-requests.show', $request) }}" class="text-blue-600 hover:underline">Detail</a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No funding requests yet.</p>
        @endif
    </div>
@endsection

