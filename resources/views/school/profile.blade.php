@extends('layouts.school')

@section('title', 'School Profile')
@section('page-title', 'School Profile')

@section('content')
    @php
        $user = auth()->user();
        $school = $school ?? $user->school;
    @endphp

    <div class="bg-surface p-8 rounded-xl border border-neutral-200 max-w-4xl">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('school.profile.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label class="block text-neutral-700 font-medium mb-2">School Logo</label>
                <div class="mb-2">
                    <img src="{{ $school?->logo_url ?? asset('images/default-avatar.svg') }}" alt="School Logo" class="w-24 h-24 rounded-xl object-cover shadow-sm">
                </div>
                <input type="file" name="logo" accept="image/jpg,image/jpeg,image/png,image/webp" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-neutral-700 font-medium mb-2">School Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $school?->name) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">NPSN</label>
                    <input type="text" name="npsn" value="{{ old('npsn', $school?->npsn) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Headmaster Name <span class="text-red-500">*</span></label>
                    <input type="text" name="headmaster_name" value="{{ old('headmaster_name', $school?->headmaster_name) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $school?->email) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Phone Number <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $school?->phone) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Accreditation Number <span class="text-red-500">*</span></label>
                    <input type="text" name="accreditation_number" value="{{ old('accreditation_number', $school?->accreditation_number) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Stellar Wallet Address</label>
                    <input type="text" name="stellar_wallet_address" value="{{ old('stellar_wallet_address', $school?->stellar_wallet_address) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-neutral-700 font-medium mb-2">Address <span class="text-red-500">*</span></label>
                    <textarea name="address" rows="3" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>{{ old('address', $school?->address) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-neutral-700 font-medium mb-2">Accreditation Document</label>
                    <input type="file" name="accreditation_document" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                    @if($school?->accreditation_document)
                        <p class="text-sm text-neutral-500 mt-2">Current document: <a href="{{ Storage::url($school->accreditation_document) }}" target="_blank" class="text-primary hover:underline">View Document</a></p>
                    @endif
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-6 py-2.5 rounded-full font-semibold transition-all btn-press">
                    Save Profile
                </button>
                <a href="{{ route('school.dashboard') }}" class="bg-neutral-100 hover:bg-neutral-200 text-neutral-700 px-6 py-2.5 rounded-full font-medium transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
