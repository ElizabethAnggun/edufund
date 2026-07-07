@extends('layouts.school')

@section('title', 'Funding Requests')
@section('page-title', 'Daftar Funding Request')

@section('content')
    <div class="bg-surface p-6 rounded-xl border border-neutral-200">
        <h3 class="text-lg font-semibold text-neutral-900 mb-4">Daftar Semua Funding Request</h3>
        
        @if($fundingRequests->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-neutral-200">
                            <th class="text-left py-3 px-4 font-medium text-neutral-500 text-sm">Judul</th>
                            <th class="text-left py-3 px-4 font-medium text-neutral-500 text-sm">Nama Siswa</th>
                            <th class="text-left py-3 px-4 font-medium text-neutral-500 text-sm">Kategori</th>
                            <th class="text-left py-3 px-4 font-medium text-neutral-500 text-sm">Nominal</th>
                            <th class="text-left py-3 px-4 font-medium text-neutral-500 text-sm">Status</th>
                            <th class="text-left py-3 px-4 font-medium text-neutral-500 text-sm">Deadline</th>
                            <th class="text-left py-3 px-4 font-medium text-neutral-500 text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fundingRequests as $request)
                            <tr class="border-b border-neutral-100 hover:bg-primary-soft transition-colors">
                                <td class="py-3 px-4 text-sm text-neutral-900">{{ $request->title }}</td>
                                <td class="py-3 px-4 text-sm text-neutral-700">{{ $request->studentProfile->user->name }}</td>
                                <td class="py-3 px-4 text-sm text-neutral-700">{{ ucwords(str_replace('_', ' ', $request->funding_category->value)) }}</td>
                                <td class="py-3 px-4 text-sm text-neutral-700">Rp {{ number_format($request->total_amount, 2) }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 text-xs rounded-full 
                                        {{ $request->status == \App\Enums\FundingRequestStatus::PENDING_SCHOOL_APPROVAL ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $request->status == \App\Enums\FundingRequestStatus::APPROVED ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $request->status == \App\Enums\FundingRequestStatus::REJECTED ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $request->status == \App\Enums\FundingRequestStatus::DRAFT ? 'bg-neutral-100 text-neutral-700' : '' }}
                                    ">
                                        {{ ucwords(str_replace('_', ' ', $request->status->value)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-neutral-700">{{ $request->deadline->format('d M Y') }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('school.funding-requests.show', $request) }}" class="text-primary text-sm font-medium hover:text-primary-hover transition-colors">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $fundingRequests->links() }}
            </div>
        @else
            <p class="text-neutral-600">Tidak ada funding request.</p>
        @endif
    </div>
@endsection
