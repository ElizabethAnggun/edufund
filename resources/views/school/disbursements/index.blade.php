@extends('layouts.school')

@section('title', 'Disbursements')
@section('page-title', 'Disbursements')

@section('content')
    <div class="mb-6">
        <a href="{{ route('school.dashboard') }}" class="text-primary hover:text-primary-hover font-medium text-sm">
            ← Back to Dashboard
        </a>
    </div>

    <div class="bg-surface p-8 rounded-xl border border-neutral-200">
        <h2 class="text-2xl font-bold text-neutral-900 mb-6">Fund Disbursements to Students</h2>

        @if($disbursements->isEmpty())
            <div class="text-center py-12">
                <p class="text-neutral-500">No disbursements have been made yet.</p>
                <p class="text-sm text-neutral-400 mt-2">When you release milestone funds to a student, the record will appear here.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-neutral-200 text-sm text-neutral-500">
                            <th class="py-3 pr-4">Student</th>
                            <th class="py-3 pr-4">Milestone</th>
                            <th class="py-3 pr-4">Amount</th>
                            <th class="py-3 pr-4">Status</th>
                            <th class="py-3 pr-4">Released</th>
                            <th class="py-3 pr-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($disbursements as $disbursement)
                            <tr class="border-b border-neutral-100 hover:bg-background transition-all">
                                <td class="py-4 pr-4">
                                    <p class="font-medium text-neutral-900">{{ $disbursement->studentProfile->user->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-neutral-500">{{ $disbursement->fundingRequest->title }}</p>
                                </td>
                                <td class="py-4 pr-4 text-neutral-700">
                                    {{ $disbursement->milestone->title ?? '-' }}
                                </td>
                                <td class="py-4 pr-4 font-semibold text-primary">
                                    ${{ number_format($disbursement->amount, 2) }}
                                </td>
                                <td class="py-4 pr-4">
                                    @php
                                        $statusClass = match($disbursement->status) {
                                            \App\Enums\DisbursementStatus::COMPLETED => 'bg-green-100 text-green-800',
                                            \App\Enums\DisbursementStatus::PENDING => 'bg-yellow-100 text-yellow-800',
                                            \App\Enums\DisbursementStatus::FAILED => 'bg-red-100 text-red-800',
                                            default => 'bg-neutral-100 text-neutral-800'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucwords($disbursement->status->value) }}
                                    </span>
                                </td>
                                <td class="py-4 pr-4 text-neutral-600 text-sm">
                                    {{ $disbursement->released_at ? $disbursement->released_at->format('M d, Y H:i') : '-' }}
                                </td>
                                <td class="py-4 pr-4">
                                    <a href="{{ route('school.disbursements.show', $disbursement) }}" class="text-primary hover:text-primary-hover font-medium text-sm">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $disbursements->links() }}
            </div>
        @endif
    </div>
@endsection
