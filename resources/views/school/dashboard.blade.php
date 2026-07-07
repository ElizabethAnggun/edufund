@extends('layouts.school')

@section('title', 'Dashboard')
@section('page-title', 'School Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-surface p-6 rounded-xl border border-neutral-200 card-enhanced">
            <h3 class="text-sm font-medium text-neutral-500 mb-2">Menunggu Persetujuan</h3>
            <p class="text-2xl font-bold text-primary">{{ $pendingCount }}</p>
        </div>
        <div class="bg-surface p-6 rounded-xl border border-neutral-200 card-enhanced">
            <h3 class="text-sm font-medium text-neutral-500 mb-2">Disetujui</h3>
            <p class="text-2xl font-bold text-green-600">{{ $approvedCount }}</p>
        </div>
        <div class="bg-surface p-6 rounded-xl border border-neutral-200 card-enhanced">
            <h3 class="text-sm font-medium text-neutral-500 mb-2">Ditolak</h3>
            <p class="text-2xl font-bold text-red-500">{{ $rejectedCount }}</p>
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
                                Oleh: {{ $request->studentProfile->user->name }}
                                <span class="mx-2">&middot;</span>
                                Rp {{ number_format($request->total_amount, 2) }}
                                <span class="mx-2">&middot;</span>
                                <span class="px-2 py-0.5 text-xs rounded-full 
                                    {{ $request->status == \App\Enums\FundingRequestStatus::PENDING_SCHOOL_APPROVAL ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $request->status == \App\Enums\FundingRequestStatus::APPROVED ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $request->status == \App\Enums\FundingRequestStatus::REJECTED ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ ucwords(str_replace('_', ' ', $request->status->value)) }}
                                </span>
                            </p>
                        </div>
                        <a href="{{ route('school.funding-requests.show', $request) }}" class="text-primary text-sm font-medium hover:text-primary-hover transition-colors">Detail</a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-neutral-600">No funding requests yet.</p>
        @endif
    </div>
@endsection

