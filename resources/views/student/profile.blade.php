@extends('layouts.student')

@section('title', 'Profile')
@section('page-title', 'Student Profile')

@section('content')
    @php
        $user = auth()->user();
        $profile = $profile ?? $user->studentProfile;
    @endphp

    <div class="bg-surface p-8 rounded-xl border border-neutral-200 max-w-4xl">
        <form action="{{ route('student.profile') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label class="block text-neutral-700 font-medium mb-2">Profile Photo</label>
                @if($profile && $profile->profile_photo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="Profile" class="w-24 h-24 rounded-full object-cover shadow-sm">
                    </div>
                @endif
                <input type="file" name="profile_photo" accept="image/*" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn', $profile?->nisn) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">NIM</label>
                    <input type="text" name="nim" value="{{ old('nim', $profile?->nim) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $profile?->phone) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $profile?->date_of_birth?->format('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Education Level</label>
                    <select name="education_level" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all bg-white" required>
                        @foreach(\App\Enums\EducationLevel::cases() as $level)
                            <option value="{{ $level->value }}" {{ old('education_level', $profile?->education_level?->value) === $level->value ? 'selected' : '' }}>
                                {{ strtoupper($level->value) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Major</label>
                    <input type="text" name="major" value="{{ old('major', $profile?->major) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Semester</label>
                    <input type="number" name="semester" value="{{ old('semester', $profile?->semester) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required min="1">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">GPA</label>
                    <input type="number" name="gpa" value="{{ old('gpa', $profile?->gpa) }}" step="0.01" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" min="0" max="4">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-neutral-700 font-medium mb-2">Address</label>
                <textarea name="address" rows="3" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>{{ old('address', $profile?->address) }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-neutral-700 font-medium mb-2">Bio</label>
                <textarea name="bio" rows="4" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">{{ old('bio', $profile?->bio) }}</textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-6 py-2.5 rounded-full font-semibold transition-all btn-press">
                    Save Profile
                </button>
                <a href="{{ route('student.dashboard') }}" class="bg-neutral-100 hover:bg-neutral-200 text-neutral-700 px-6 py-2.5 rounded-full font-medium transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
