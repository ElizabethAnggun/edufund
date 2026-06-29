@extends('layouts.student')

@section('title', 'Funding Requests')
@section('page-title', 'Funding Requests')

@section('content')
    <div class="mb-4">
        <a href="{{ route('student.funding-requests.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
            Create New Request
        </a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        @if($fundingRequests->count() > 0)
            <div class="space-y-4">
                @foreach($fundingRequests as $request)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-md">
                        <div>
                            <h4 class="font-medium text-gray-800">{{ $request->title }}</h4>
                            <p class="text-sm text-gray-600">
                                {{ number_format($request->total_amount, 2) }} {{ $request->currency }} • 
                                Deadline: {{ $request->deadline->format('M d, Y') }} •
                                <span class="px-2 py-1 text-xs rounded-full {{ $request->status === \App\Enums\FundingRequestStatus::DRAFT ? 'bg-yellow-100 text-yellow-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ ucwords(str_replace('_', ' ', $request->status->value)) }}
                                </span>
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('student.funding-requests.show', $request) }}" class="text-blue-600 hover:underline">View</a>
                            @if($request->status === \App\Enums\FundingRequestStatus::DRAFT)
                                <a href="{{ route('student.funding-requests.edit', $request) }}" class="text-green-600 hover:underline">Edit</a>
                                <form action="{{ route('student.funding-requests.destroy', $request) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this request?')">Delete</button>
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
            <p class="text-gray-600">No funding requests yet. <a href="{{ route('student.funding-requests.create') }}" class="text-blue-600 hover:underline">Create your first request</a>.</p>
        @endif
    </div>
@endsection
