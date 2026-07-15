@extends('layouts.school')

@section('title', 'Verification History')
@section('page-title', 'Verification History')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Verification History</h2>
        <p class="text-neutral-500">Track milestone verification activities</p>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        @if(isset($verifications) && $verifications->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-neutral-500 text-sm">
                            <th class="pb-4 pr-4">Milestone</th>
                            <th class="pb-4 pr-4">Student</th>
                            <th class="pb-4 pr-4">Status</th>
                            <th class="pb-4 pr-4">Date</th>
                            <th class="pb-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($verifications as $verification)
                            <tr class="border-t border-neutral-100">
                                <td class="py-4 pr-4 font-semibold text-neutral-900">{{ $verification->milestone->title ?? 'N/A' }}</td>
                                <td class="py-4 pr-4 text-neutral-600">{{ $verification->milestone->fundingRequest->studentProfile->user->name ?? 'Unknown' }}</td>
                                <td class="py-4 pr-4">
                                    @php
                                        $statusClass = match($verification->status) {
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-neutral-100 text-neutral-800'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($verification->status) }}
                                    </span>
                                </td>
                                <td class="py-4 pr-4 text-neutral-600">{{ $verification->created_at->format('M d, Y') }}</td>
                                <td class="py-4">
                                    <a href="#" class="text-primary hover:text-primary-hover text-sm font-medium">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <p class="text-neutral-500">No verification history yet</p>
            </div>
        @endif
    </div>
@endsection