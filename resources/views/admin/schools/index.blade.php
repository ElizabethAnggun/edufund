@extends('layouts.admin')

@section('title', 'School Verification')
@section('page-title', 'School Verification')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">School Verification</h2>
        <p class="text-neutral-500">Review and verify educational institutions</p>
    </div>

    <!-- Tabs -->
    <div class="flex gap-2 mb-6 border-b border-neutral-200">
        <a href="{{ route('admin.schools.index', ['tab' => 'pending']) }}" class="px-4 py-2.5 text-sm font-medium {{ $tab === 'pending' ? 'text-primary border-b-2 border-primary' : 'text-neutral-500 hover:text-neutral-700' }}">
            Pending <span class="ml-1 px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-xs">{{ $counts['pending'] }}</span>
        </a>
        <a href="{{ route('admin.schools.index', ['tab' => 'verified']) }}" class="px-4 py-2.5 text-sm font-medium {{ $tab === 'verified' ? 'text-primary border-b-2 border-primary' : 'text-neutral-500 hover:text-neutral-700' }}">
            Verified <span class="ml-1 px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs">{{ $counts['verified'] }}</span>
        </a>
        <a href="{{ route('admin.schools.index', ['tab' => 'rejected']) }}" class="px-4 py-2.5 text-sm font-medium {{ $tab === 'rejected' ? 'text-primary border-b-2 border-primary' : 'text-neutral-500 hover:text-neutral-700' }}">
            Rejected <span class="ml-1 px-2 py-0.5 bg-red-100 text-red-800 rounded-full text-xs">{{ $counts['rejected'] }}</span>
        </a>
    </div>

    <div class="bg-surface rounded-xl border border-neutral-200">
        @if($schools->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-medium text-neutral-500 uppercase tracking-wider border-b border-neutral-200">
                            <th class="px-6 py-4">School</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Accreditation #</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Registered</th>
                            <th class="px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($schools as $school)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary-active flex items-center justify-center text-white font-bold text-sm">
                                            {{ substr($school->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-neutral-900">{{ $school->name }}</p>
                                            <p class="text-xs text-neutral-500">{{ $school->user->name ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-600">{{ $school->email }}</td>
                                <td class="px-6 py-4 text-sm text-neutral-600 font-mono">{{ $school->accreditation_number }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $badgeClass = match($school->verification_status->value) {
                                            'verified' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            default => 'bg-neutral-100 text-neutral-800'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                        {{ ucfirst($school->verification_status->value) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-500">{{ $school->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.schools.show', $school) }}" class="text-primary hover:text-primary-hover font-medium text-sm">Review →</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-neutral-200">
                {{ $schools->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="text-neutral-500">No schools in this category</p>
            </div>
        @endif
    </div>
@endsection