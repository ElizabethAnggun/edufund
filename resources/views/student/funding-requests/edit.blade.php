@extends('layouts.student')

@section('title', 'Edit Funding Request')
@section('page-title', 'Edit Funding Request')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 max-w-4xl">
        <form action="{{ route('student.funding-requests.update', $fundingRequest) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Title</label>
                <input type="text" name="title" value="{{ old('title', $fundingRequest->title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Description</label>
                <textarea name="description" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('description', $fundingRequest->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Target Amount</label>
                    <input type="number" name="total_amount" value="{{ old('total_amount', $fundingRequest->total_amount) }}" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required min="0">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline', $fundingRequest->deadline->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Category</label>
                    <select name="funding_category" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @foreach(\App\Enums\FundingCategory::cases() as $category)
                            <option value="{{ $category->value }}" {{ old('funding_category', $fundingRequest->funding_category->value) === $category->value ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $category->value)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium">
                    Update Draft
                </button>
                <a href="{{ route('student.funding-requests.show', $fundingRequest) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
