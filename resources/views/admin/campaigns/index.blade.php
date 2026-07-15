@extends('layouts.admin')

@section('title', 'Campaigns Management')
@section('page-title', 'Campaigns Management')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-neutral-900 mb-2">Campaigns Management</h2>
        <p class="text-neutral-500">Monitor all campaigns on the platform</p>
    </div>

    <div class="bg-surface rounded-xl border border-neutral-200">
        @if($campaigns->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-medium text-neutral-500 uppercase tracking-wider border-b border-neutral-200">
                            <th class="px-6 py-4">Campaign</th>
                            <th class="px-6 py-4">School</th>
                            <th class="px-6 py-4">Progress</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Amount Raised</th>
                            <th class="px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($campaigns as $campaign)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-neutral-900">{{ $campaign->title }}</div>
                                    <div class="text-xs text-neutral-500">ID: {{ $campaign->id }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-600">{{ $campaign->school->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs text-neutral-600">Goal: {{ number_format($campaign->goal_amount, 2) }} XLM</span>
                                        <span class="text-xs font-semibold text-primary">
                                            {{ $campaign->goal_amount > 0 ? round(($campaign->current_amount / $campaign->goal_amount) * 100, 1) : 0 }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-neutral-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-primary to-primary-hover h-2 rounded-full" style="width: {{ $campaign->goal_amount > 0 ? min(($campaign->current_amount / $campaign->goal_amount) * 100, 100) : 0 }}%"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = match($campaign->status->value ?? $campaign->status) {
                                            'active' => 'bg-green-100 text-green-800',
                                            'paused' => 'bg-yellow-100 text-yellow-800',
                                            'completed' => 'bg-blue-100 text-blue-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            default => 'bg-neutral-100 text-neutral-800'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($campaign->status->value ?? $campaign->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-primary">{{ number_format($campaign->current_amount, 2) }} XLM</td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.campaigns.toggle-status', $campaign) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-primary hover:text-primary-hover font-medium text-sm px-3 py-1 rounded-lg hover:bg-primary-soft transition-all">
                                            {{ $campaign->status->value === 'active' ? 'Pause' : 'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-neutral-200">
                {{ $campaigns->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
                <p class="text-neutral-500">No campaigns found</p>
            </div>
        @endif
    </div>
@endsection