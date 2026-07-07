@extends('layouts.student')

@section('title', 'Funding Requests')
@section('page-title', 'Funding Requests')

@section('content')
    <div class="mb-6">
        <a href="{{ route('student.funding-requests.create') }}" class="btn-primary inline-block font-semibold">
            Create New Request
        </a>
    </div>

    <div class="bg-surface p-6 rounded-xl border border-neutral-200">
        @if($fundingRequests->count() > 0)
            <div class="space-y-3">
                @foreach($fundingRequests as $request)
                    <div class="flex items-center justify-between p-4 border border-neutral-100 rounded-lg hover:bg-primary-soft transition-colors">
                        <div>
                            <h4 class="font-medium text-neutral-900">{{ $request->title }}</h4>
                            <p class="text-sm text-neutral-500 mt-1">
                                {{ number_format($request->total_amount, 2) }} {{ $request->currency }}
                                <span class="mx-2">&middot;</span>
                                Deadline: {{ $request->deadline->format('M d, Y') }}
                                <span class="mx-2">&middot;</span>
                                <span class="px-2 py-0.5 text-xs rounded-full {{ $request->status === \App\Enums\FundingRequestStatus::DRAFT ? 'bg-neutral-100 text-neutral-700' : 'bg-primary-soft text-primary' }}">
                                    {{ ucwords(str_replace('_', ' ', $request->status->value)) }}
                                </span>
                            </p>
                        </div>
                        <div class="flex gap-3 items-center">
                            <a href="{{ route('student.funding-requests.show', $request) }}" class="text-primary text-sm font-medium hover:text-primary-hover transition-colors">View</a>
                            @if($request->status === \App\Enums\FundingRequestStatus::DRAFT)
                                <a href="{{ route('student.funding-requests.edit', $request) }}" class="text-neutral-500 text-sm hover:text-neutral-700 transition-colors">Edit</a>
                                <form action="{{ route('student.funding-requests.destroy', $request) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 text-sm hover:text-red-700 transition-colors" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $fundingRequests->links() }}
            </div>
        @else
            <p class="text-neutral-600">No funding requests yet. <a href="{{ route('student.funding-requests.create') }}" class="text-primary font-medium hover:text-primary-hover transition-colors">Create your first request</a>.</p>
        @endif
    </div>
@endsection
