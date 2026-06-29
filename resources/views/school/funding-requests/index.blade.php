@extends('layouts.school')

@section('title', 'Funding Requests')
@section('page-title', 'Daftar Funding Request')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Semua Funding Request</h3>
        
        @if($fundingRequests->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Judul</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Nama Siswa</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Kategori</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Nominal</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Status</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Deadline</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fundingRequests as $request)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $request->title }}</td>
                                <td class="py-3 px-4">{{ $request->studentProfile->user->name }}</td>
                                <td class="py-3 px-4">{{ ucwords(str_replace('_', ' ', $request->funding_category->value)) }}</td>
                                <td class="py-3 px-4">Rp {{ number_format($request->total_amount, 2) }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $request->status == \App\Enums\FundingRequestStatus::PENDING_SCHOOL_APPROVAL ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $request->status == \App\Enums\FundingRequestStatus::APPROVED ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $request->status == \App\Enums\FundingRequestStatus::REJECTED ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $request->status == \App\Enums\FundingRequestStatus::DRAFT ? 'bg-gray-100 text-gray-800' : '' }}
                                    ">
                                        {{ ucwords(str_replace('_', ' ', $request->status->value)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">{{ $request->deadline->format('d M Y') }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('school.funding-requests.show', $request) }}" class="text-blue-600 hover:underline">Detail</a>
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
            <p class="text-gray-600">Tidak ada funding request.</p>
        @endif
    </div>
@endsection
