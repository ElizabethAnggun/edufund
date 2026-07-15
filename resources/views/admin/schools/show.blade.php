@extends('layouts.admin')

@section('title', 'School Details')
@section('page-title', 'School Details')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.schools.index') }}" class="text-primary hover:text-primary-hover font-medium text-sm">
            ← Back to Schools
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface p-8 rounded-xl border border-neutral-200">
                <h2 class="text-2xl font-bold text-neutral-900 mb-6">School Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-neutral-500 mb-1">School Name</p>
                        <p class="font-semibold text-neutral-900">{{ $school->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-500 mb-1">Email</p>
                        <p class="font-mono text-neutral-900">{{ $school->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-500 mb-1">Phone</p>
                        <p class="font-mono text-neutral-900">{{ $school->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-500 mb-1">Accreditation Number</p>
                        <p class="font-mono text-neutral-900">{{ $school->accreditation_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-500 mb-1">Status</p>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($school->verification_status->value === 'verified') bg-green-100 text-green-800
                            @elseif($school->verification_status->value === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($school->verification_status->value) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-500 mb-1">Verified At</p>
                        <p class="font-semibold text-neutral-900">{{ $school->verified_at ? $school->verified_at->format('M d, Y H:i') : 'Not verified' }}</p>
                    </div>
                </div>

                @if($school->address)
                    <div class="mb-4">
                        <p class="text-sm text-neutral-500 mb-1">Address</p>
                        <p class="text-neutral-700">{{ $school->address }}</p>
                    </div>
                @endif

                <div class="mb-6">
                    <p class="text-sm text-neutral-500 mb-3">Accreditation Document</p>
                    @if($school->accreditation_document)
                        <a href="{{ Storage::url($school->accreditation_document) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg font-medium transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            View Document
                        </a>
                    @else
                        <p class="text-sm text-neutral-500">No document uploaded</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-surface p-6 rounded-xl border border-neutral-200 sticky top-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @if($school->verification_status->value === 'pending')
                        <form action="{{ route('admin.schools.verify', $school) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Approve School
                            </button>
                        </form>
                    @endif

                    @if($school->verification_status->value !== 'rejected')
                        <button onclick="document.getElementById('reject-form').classList.toggle('hidden')" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-4 rounded-lg transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject School
                        </button>

                        <form id="reject-form" action="{{ route('admin.schools.reject', $school) }}" method="POST" class="hidden w-full">
                            @csrf
                            <input type="text" name="reason" placeholder="Rejection reason..." class="w-full px-3 py-2 border border-neutral-300 rounded-lg mb-3 focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-medium py-2 px-4 rounded-lg transition-all">
                                Confirm Rejection
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection