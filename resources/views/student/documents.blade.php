@extends('layouts.student')

@section('title', 'My Documents')
@section('page-title', 'My Documents')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">My Documents</h2>
        <p class="text-neutral-500">Manage all your supporting documents</p>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        @if($documents->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($documents as $document)
                    <div class="p-4 bg-background rounded-xl border border-neutral-200">
                        <h3 class="font-semibold text-neutral-900 mb-2">{{ $document->document_type }}</h3>
                        <p class="text-sm text-neutral-500 mb-3">{{ $document->file_name }}</p>
                        <div class="flex items-center gap-3">
                            <a href="#" class="text-sm text-primary hover:text-primary-active font-medium">Download</a>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $document->verification_status ?? 'Pending' }}
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
                <p class="text-neutral-500">No documents uploaded yet</p>
            </div>
        @endif
    </div>
@endsection
