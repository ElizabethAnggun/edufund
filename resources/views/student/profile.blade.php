@extends('layouts.student')

@section('title', 'Profile')
@section('page-title', 'Student Profile')

@section('content')
    @php
        $user = auth()->user();
        $profile = $profile ?? $user->studentProfile;
        $defaultLevel = old('education_level', $profile?->education_level?->value ?? 's1');
    @endphp

    <div class="bg-surface p-8 rounded-xl border border-neutral-200 max-w-4xl"
         x-data="{ 
            educationLevel: '{{ $defaultLevel }}',
            isHighSchool: function() { return ['sma', 'smk'].includes(this.educationLevel); },
            isUniversity: function() { return !this.isHighSchool(); }
         }">
        <form action="{{ route('student.profile.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label class="block text-neutral-700 font-medium mb-2">Profile Photo</label>
                <div class="mb-2">
                    <img src="{{ $profile?->avatar_url ?? asset('images/default-avatar.svg') }}" alt="Profile" class="w-24 h-24 rounded-full object-cover shadow-sm">
                </div>
                <input type="file" name="profile_photo" accept="image/jpg,image/jpeg,image/png,image/webp" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
            </div>

            <div class="mb-6">
                <label class="block text-neutral-700 font-medium mb-2">Education Level</label>
                <select name="education_level" 
                        x-model="educationLevel"
                        class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all bg-white" 
                        required>
                    @foreach(\App\Enums\EducationLevel::cases() as $level)
                        <option value="{{ $level->value }}" {{ $defaultLevel === $level->value ? 'selected' : '' }}>
                            {{ strtoupper($level->value) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Helper Text -->
            <div class="mb-6 p-4 rounded-lg" 
                 :class="isHighSchool() ? 'bg-yellow-50 border border-yellow-200' : 'bg-blue-50 border border-blue-200'">
                <p class="text-sm font-medium" 
                   :class="isHighSchool() ? 'text-yellow-800' : 'text-blue-800'">
                    <span x-show="isHighSchool()">Please complete your school information.</span>
                    <span x-show="isUniversity()">Please complete your university information.</span>
                </p>
            </div>

            <!-- Shared Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $profile?->phone) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $profile?->date_of_birth?->format('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-neutral-700 font-medium mb-2">Address</label>
                    <textarea name="address" rows="3" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">{{ old('address', $profile?->address) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-neutral-700 font-medium mb-2">Bio</label>
                    <textarea name="bio" rows="3" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">{{ old('bio', $profile?->bio) }}</textarea>
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Parent's Name</label>
                    <input type="text" name="parent_name" value="{{ old('parent_name', $profile?->parent_name) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Parent's Income</label>
                    <input type="number" name="parent_income" step="1000" value="{{ old('parent_income', $profile?->parent_income) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" min="0">
                </div>
            </div>

            <!-- High School Fields -->
            <div x-show="isHighSchool()" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-neutral-700 font-medium mb-2">School Name</label>
                    <input type="text" name="school_name" value="{{ old('school_name', $profile?->school_name) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" :required="isHighSchool()">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">NPSN</label>
                    <input type="text" name="school_npsn" value="{{ old('school_npsn', $profile?->school_npsn) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-neutral-700 font-medium mb-2">School Address</label>
                    <textarea name="school_address" rows="3" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">{{ old('school_address', $profile?->school_address) }}</textarea>
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Major</label>
                    <input type="text" name="major" value="{{ old('major', $profile?->major) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" :required="isHighSchool()">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Class</label>
                    <input type="text" name="class" value="{{ old('class', $profile?->class) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" :required="isHighSchool()">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn', $profile?->nisn) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" :required="isHighSchool()">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Graduation Year</label>
                    <input type="number" name="graduation_year" value="{{ old('graduation_year', $profile?->graduation_year) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" min="2000" max="{{ date('Y') }}">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Ranking</label>
                    <input type="text" name="academic_rank" value="{{ old('academic_rank', $profile?->academic_rank) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" :required="isHighSchool()">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Student Status</label>
                    <input type="text" name="student_status" value="{{ old('student_status', $profile?->student_status) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                </div>
            </div>

            <!-- University Fields -->
            <div x-show="isUniversity()" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-neutral-700 font-medium mb-2">University Name</label>
                    <input type="text" name="university_name" value="{{ old('university_name', $profile?->university_name) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" :required="isUniversity()">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-neutral-700 font-medium mb-2">University Address</label>
                    <textarea name="university_address" rows="3" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">{{ old('university_address', $profile?->university_address) }}</textarea>
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Faculty</label>
                    <input type="text" name="faculty" value="{{ old('faculty', $profile?->faculty) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" :required="isUniversity()">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Study Program</label>
                    <input type="text" name="study_program" value="{{ old('study_program', $profile?->study_program) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" :required="isUniversity()">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">NIM</label>
                    <input type="text" name="nim" value="{{ old('nim', $profile?->nim) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" :required="isUniversity()">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Semester</label>
                    <input type="number" name="semester" value="{{ old('semester', $profile?->semester) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" :required="isUniversity()" min="1">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">GPA (IPK)</label>
                    <input type="number" name="gpa" value="{{ old('gpa', $profile?->gpa) }}" step="0.01" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" :required="isUniversity()" min="0" max="4">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Expected Graduation</label>
                    <input type="date" name="expected_graduation" value="{{ old('expected_graduation', $profile?->expected_graduation?->format('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Scholarship Status</label>
                    <input type="text" name="scholarship_status" value="{{ old('scholarship_status', $profile?->scholarship_status) }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                </div>
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
