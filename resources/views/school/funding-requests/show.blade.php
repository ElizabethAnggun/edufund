@extends('layouts.school')

@section('title', 'Detail Funding Request')
@section('page-title', 'Detail Funding Request')

@section('content')
    <div class="space-y-6">
        <div class="bg-surface p-8 rounded-xl border border-neutral-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-neutral-900">{{ $fundingRequest->title }}</h3>
                <span class="px-3 py-1 text-sm rounded-full font-semibold
                    {{ $fundingRequest->status == \App\Enums\FundingRequestStatus::PENDING_SCHOOL_APPROVAL ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $fundingRequest->status == \App\Enums\FundingRequestStatus::APPROVED ? 'bg-green-100 text-green-800' : '' }}
                    {{ $fundingRequest->status == \App\Enums\FundingRequestStatus::REJECTED ? 'bg-red-100 text-red-800' : '' }}
                    {{ $fundingRequest->status == \App\Enums\FundingRequestStatus::DRAFT ? 'bg-neutral-100 text-neutral-700' : '' }}
                ">
                    {{ ucwords(str_replace('_', ' ', $fundingRequest->status->value)) }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-neutral-50 rounded-xl p-4">
                    <p class="text-sm text-neutral-500 mb-1">Kategori</p>
                    <p class="font-semibold text-neutral-900">{{ ucwords(str_replace('_', ' ', $fundingRequest->funding_category->value)) }}</p>
                </div>
                <div class="bg-neutral-50 rounded-xl p-4">
                    <p class="text-sm text-neutral-500 mb-1">Nominal Target</p>
                    <p class="font-semibold text-neutral-900">Rp {{ number_format($fundingRequest->total_amount, 2) }}</p>
                </div>
                <div class="bg-neutral-50 rounded-xl p-4">
                    <p class="text-sm text-neutral-500 mb-1">Deadline</p>
                    <p class="font-semibold text-neutral-900">{{ $fundingRequest->deadline->format('d M Y') }}</p>
                </div>
                <div class="bg-neutral-50 rounded-xl p-4">
                    <p class="text-sm text-neutral-500 mb-1">Mata Uang</p>
                    <p class="font-semibold text-neutral-900">{{ $fundingRequest->currency }}</p>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-sm text-neutral-500 mb-2">Deskripsi</p>
                <p class="text-neutral-700 leading-relaxed">{{ $fundingRequest->description }}</p>
            </div>

            @if(in_array($fundingRequest->status, [\App\Enums\FundingRequestStatus::DRAFT, \App\Enums\FundingRequestStatus::PENDING_SCHOOL_APPROVAL]))
                <div class="flex gap-3">
                    <form action="{{ route('school.funding-requests.approve', $fundingRequest) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-full font-semibold transition-all btn-press">
                            Setujui
                        </button>
                    </form>
                    <button type="button" onclick="document.getElementById('reject-modal').classList.remove('hidden')" class="bg-neutral-100 hover:bg-neutral-200 text-red-500 px-5 py-2.5 rounded-full font-semibold transition-all">
                        Tolak
                    </button>
                </div>
            @endif

            @if($fundingRequest->status == \App\Enums\FundingRequestStatus::REJECTED && $fundingRequest->rejection_reason)
                <div class="mt-6 pt-6 border-t border-neutral-200">
                    <p class="text-sm text-neutral-500 mb-2">Alasan Penolakan</p>
                    <p class="text-red-600">{{ $fundingRequest->rejection_reason }}</p>
                </div>
            @endif
        </div>

        <div class="bg-surface p-8 rounded-xl border border-neutral-200">
            <h4 class="text-lg font-semibold text-neutral-900 mb-4">Informasi Siswa</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-neutral-500">Nama</p>
                    <p class="font-medium text-neutral-900">{{ $fundingRequest->studentProfile->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-neutral-500">NIM</p>
                    <p class="font-medium text-neutral-900">{{ $fundingRequest->studentProfile->nim }}</p>
                </div>
                <div>
                    <p class="text-sm text-neutral-500">Jurusan</p>
                    <p class="font-medium text-neutral-900">{{ $fundingRequest->studentProfile->major }}</p>
                </div>
                <div>
                    <p class="text-sm text-neutral-500">No. HP</p>
                    <p class="font-medium text-neutral-900">{{ $fundingRequest->studentProfile->phone }}</p>
                </div>
            </div>
        </div>

        <div class="bg-surface p-8 rounded-xl border border-neutral-200">
            <h4 class="text-lg font-semibold text-neutral-900 mb-4">Informasi Sekolah</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-neutral-500">Nama Sekolah</p>
                    <p class="font-medium text-neutral-900">{{ $fundingRequest->school->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-neutral-500">Alamat</p>
                    <p class="font-medium text-neutral-900">{{ $fundingRequest->school->address }}</p>
                </div>
            </div>
        </div>

        <div class="bg-surface p-8 rounded-xl border border-neutral-200">
            <h4 class="text-lg font-semibold text-neutral-900 mb-4">Dokumen Pendukung</h4>
            @if($fundingRequest->supportingDocuments->count() > 0)
                <div class="space-y-3">
                    @foreach($fundingRequest->supportingDocuments as $document)
                        <div class="flex items-center justify-between p-4 border border-neutral-100 rounded-xl hover:bg-primary-soft transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-neutral-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-neutral-900">{{ $document->file_name }}</p>
                                    <p class="text-sm text-neutral-500">
                                        {{ ucwords(str_replace('_', ' ', $document->document_type->value)) }}
                                        <span class="mx-1">&middot;</span>
                                        {{ number_format($document->file_size / 1024, 1) }} KB
                                    </p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-primary text-sm font-medium hover:text-primary-hover transition-colors">Download</a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-neutral-600">Tidak ada dokumen pendukung.</p>
            @endif
        </div>

        <div>
            <a href="{{ route('school.funding-requests.index') }}" class="inline-flex items-center gap-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 px-5 py-2.5 rounded-full font-medium transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="reject-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-surface p-8 rounded-xl shadow-lg w-full max-w-md border border-neutral-200">
            <h3 class="text-xl font-semibold text-neutral-900 mb-4">Tolak Funding Request</h3>
            <form action="{{ route('school.funding-requests.reject', $fundingRequest) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Alasan Penolakan</label>
                    <textarea name="rejection_reason" rows="4" class="w-full border border-neutral-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required minlength="10"></textarea>
                    @error('rejection_reason')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" class="bg-neutral-100 hover:bg-neutral-200 text-neutral-700 px-5 py-2.5 rounded-full font-medium transition-all">
                        Batal
                    </button>
                    <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-full font-semibold transition-all btn-press">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
