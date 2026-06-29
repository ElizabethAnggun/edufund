@extends('layouts.school')

@section('title', 'Detail Funding Request')
@section('page-title', 'Detail Funding Request')

@section('content')
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800">{{ $fundingRequest->title }}</h3>
                <span class="px-3 py-1 text-sm rounded-full 
                    {{ $fundingRequest->status == \App\Enums\FundingRequestStatus::PENDING_SCHOOL_APPROVAL ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $fundingRequest->status == \App\Enums\FundingRequestStatus::APPROVED ? 'bg-green-100 text-green-800' : '' }}
                    {{ $fundingRequest->status == \App\Enums\FundingRequestStatus::REJECTED ? 'bg-red-100 text-red-800' : '' }}
                    {{ $fundingRequest->status == \App\Enums\FundingRequestStatus::DRAFT ? 'bg-gray-100 text-gray-800' : '' }}
                ">
                    {{ ucwords(str_replace('_', ' ', $fundingRequest->status->value)) }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-600">Kategori</p>
                    <p class="font-medium">{{ ucwords(str_replace('_', ' ', $fundingRequest->funding_category->value)) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Nominal Target</p>
                    <p class="font-medium">Rp {{ number_format($fundingRequest->total_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Deadline</p>
                    <p class="font-medium">{{ $fundingRequest->deadline->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Mata Uang</p>
                    <p class="font-medium">{{ $fundingRequest->currency }}</p>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-sm text-gray-600">Deskripsi</p>
                <p class="mt-1">{{ $fundingRequest->description }}</p>
            </div>

            @if(in_array($fundingRequest->status, [\App\Enums\FundingRequestStatus::DRAFT, \App\Enums\FundingRequestStatus::PENDING_SCHOOL_APPROVAL]))
                <div class="flex gap-3">
                    <form action="{{ route('school.funding-requests.approve', $fundingRequest) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium">
                            Setujui
                        </button>
                    </form>
                    <button type="button" onclick="document.getElementById('reject-modal').classList.remove('hidden')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium">
                        Tolak
                    </button>
                </div>
            @endif

            @if($fundingRequest->status == \App\Enums\FundingRequestStatus::REJECTED && $fundingRequest->rejection_reason)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600">Alasan Penolakan</p>
                    <p class="mt-1 text-red-700">{{ $fundingRequest->rejection_reason }}</p>
                </div>
            @endif
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Informasi Siswa</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nama</p>
                    <p class="font-medium">{{ $fundingRequest->studentProfile->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">NIM</p>
                    <p class="font-medium">{{ $fundingRequest->studentProfile->nim }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jurusan</p>
                    <p class="font-medium">{{ $fundingRequest->studentProfile->major }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">No. HP</p>
                    <p class="font-medium">{{ $fundingRequest->studentProfile->phone }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Informasi Sekolah</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nama Sekolah</p>
                    <p class="font-medium">{{ $fundingRequest->school->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Alamat</p>
                    <p class="font-medium">{{ $fundingRequest->school->address }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Dokumen Pendukung</h4>
            @if($fundingRequest->supportingDocuments->count() > 0)
                <div class="space-y-3">
                    @foreach($fundingRequest->supportingDocuments as $document)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-md">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $document->file_name }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ ucwords(str_replace('_', ' ', $document->document_type->value)) }} •
                                        {{ number_format($document->file_size / 1024, 1) }} KB
                                    </p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Download</a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">Tidak ada dokumen pendukung.</p>
            @endif
        </div>

        <div>
            <a href="{{ route('school.funding-requests.index') }}" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="reject-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Tolak Funding Request</h3>
            <form action="{{ route('school.funding-requests.reject', $fundingRequest) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                    <textarea name="rejection_reason" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" required minlength="10"></textarea>
                    @error('rejection_reason')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md font-medium">
                        Batal
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
