@extends('layouts.student')

@section('title', 'Create Funding Request')
@section('page-title', 'Create Funding Request')

@section('content')
    <div class="bg-surface p-8 rounded-xl border border-neutral-200 max-w-4xl">
        <form action="{{ route('student.funding-requests.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-neutral-700 font-medium mb-2">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>
            </div>

            <div class="mb-6">
                <label class="block text-neutral-700 font-medium mb-2">Description</label>
                <textarea name="description" rows="5" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Target Amount</label>
                    <input type="number" name="total_amount" value="{{ old('total_amount') }}" step="0.01" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required min="0">
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline') }}" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>
                </div>
                <div>
                    <label class="block text-neutral-700 font-medium mb-2">Category</label>
                    <select name="funding_category" class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all bg-white" required>
                        @foreach(\App\Enums\FundingCategory::cases() as $category)
                            <option value="{{ $category->value }}" {{ old('funding_category') === $category->value ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $category->value)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-6 py-2.5 rounded-full font-semibold transition-all btn-press">
                    Save as Draft
                </button>
                <a href="{{ route('student.funding-requests.index') }}" class="bg-neutral-100 hover:bg-neutral-200 text-neutral-700 px-6 py-2.5 rounded-full font-medium transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
