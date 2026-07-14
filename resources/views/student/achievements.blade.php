@extends('layouts.student')

@section('title', 'Achievements')
@section('page-title', 'My Achievements')

@section('content')
    <div class="mb-6">
        <a href="{{ route('student.dashboard') }}" class="text-primary hover:text-primary-hover font-medium text-sm">
            ← Back to Dashboard
        </a>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        @if(isset($achievements) && $achievements->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($achievements as $achievement)
                    <div class="p-6 bg-background rounded-xl border border-neutral-200 hover:border-primary-soft transition-all hover-lift">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary to-primary-active flex items-center justify-center text-white font-bold text-2xl mb-4 mx-auto">
                            {{ substr($achievement->title, 0, 1) }}
                        </div>
                        <h4 class="text-lg font-semibold text-neutral-900 text-center mb-2">{{ $achievement->title }}</h4>
                        <p class="text-sm text-neutral-500 text-center mb-4">{{ $achievement->description }}</p>
                        <div class="text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-primary-soft text-primary">
                                Earned {{ $achievement->earned_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-20 h-20 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-neutral-500 text-lg mb-2">No achievements yet</p>
                <p class="text-sm text-neutral-400">Complete milestones and funding requests to earn achievements</p>
            </div>
        @endif
    </div>
@endsection