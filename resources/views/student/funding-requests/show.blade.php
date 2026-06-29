@extends('layouts.student')

@section('title', 'Funding Request')
@section('page-title', 'Funding Request')

@section('content')
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold text-gray-800">{{ $fundingRequest->title }}</h2>
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $fundingRequest->status === \App\Enums\FundingRequestStatus::DRAFT ? 'bg-yellow-100 text-yellow-800' : 'bg-purple-100 text-purple-800' }}">
                    {{ ucwords(str_replace('_', ' ', $fundingRequest->status->value)) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-600">Target Amount</p>
                    <p class="text-xl font-semibold text-gray-800">{{ number_format($fundingRequest->total_amount, 2) }} {{ $fundingRequest->currency }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Deadline</p>
                    <p class="text-xl font-semibold text-gray-800">{{ $fundingRequest->deadline->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Category</p>
                    <p class="text-xl font-semibold text-gray-800">{{ ucwords(str_replace('_', ' ', $fundingRequest->funding_category->value)) }}</p>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-800 mb-2">Description</h3>
                <p class="text-gray-600">{{ $fundingRequest->description }}</p>
            </div>

            <div class="flex gap-3">
                @if($fundingRequest->status === \App\Enums\FundingRequestStatus::DRAFT)
                    <a href="{{ route('student.funding-requests.edit', $fundingRequest) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium">
                        Edit
                    </a>
                    <form action="{{ route('student.funding-requests.submit', $fundingRequest) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium" onclick="return confirm('Are you sure you want to submit this request?')">
                            Submit for Approval
                        </button>
                    </form>
                    <form action="{{ route('student.funding-requests.destroy', $fundingRequest) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium" onclick="return confirm('Are you sure you want to delete this request?')">
                            Delete
                        </button>
                    </form>
                @endif
                <a href="{{ route('student.funding-requests.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md font-medium">
                    Back
                </a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Supporting Documents</h3>
            
            @if($fundingRequest->status === \App\Enums\FundingRequestStatus::DRAFT)
                <form action="{{ route('student.funding-requests.documents.upload', $fundingRequest) }}" method="POST" enctype="multipart/form-data" class="mb-6 p-4 border-2 border-dashed border-gray-300 rounded-md">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Document Type</label>
                            <select name="document_type" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                @foreach(\App\Enums\DocumentType::cases() as $type)
                                    <option value="{{ $type->value }}">
                                        {{ ucwords(str_replace('_', ' ', $type->value)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">File (PDF/JPG/PNG, max 5MB)</label>
                            <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                        Upload Document
                    </button>
                </form>
            @endif

            @if($documents->count() > 0)
                <div class="space-y-3">
                    @foreach($documents as $document)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-md">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $document->file_name }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ ucwords(str_replace('_', ' ', $document->document_type->value)) }} •
                                        {{ number_format($document->file_size / 1024, 1) }} KB
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Download</a>
                                @if($fundingRequest->status === \App\Enums\FundingRequestStatus::DRAFT)
                                    <form action="{{ route('student.funding-requests.documents.delete', [$fundingRequest, $document]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this document?')">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">No supporting documents uploaded yet.</p>
            @endif
        </div>
    </div>
@endsection
