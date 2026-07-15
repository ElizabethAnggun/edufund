@extends('layouts.school')

@section('title', 'Students')
@section('page-title', 'Student List')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Students</h2>
        <p class="text-neutral-500">Manage students under your school</p>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        @if(isset($students) && $students->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-neutral-500 text-sm">
                            <th class="pb-4 pr-4">Name</th>
                            <th class="pb-4 pr-4">Email</th>
                            <th class="pb-4 pr-4">Funding Requests</th>
                            <th class="pb-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr class="border-t border-neutral-100">
                                <td class="py-4 pr-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary-active flex items-center justify-center text-white font-bold text-sm">
                                            {{ substr($student->user->name ?? 'S', 0, 1) }}
                                        </div>
                                        <span class="font-semibold text-neutral-900">{{ $student->user->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 pr-4 text-neutral-600">{{ $student->user->email ?? '' }}</td>
                                <td class="py-4 pr-4">{{ $student->fundingRequests->count() }}</td>
                                <td class="py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $students->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="text-neutral-500">No students found</p>
            </div>
        @endif
    </div>
@endsection