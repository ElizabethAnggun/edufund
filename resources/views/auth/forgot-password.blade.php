@extends('layouts.auth')

@section('title', 'Forgot Password — ' . config('app.name', 'EduFund'))

@section('content')
    <div class="animate-fade-in-up">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-neutral-900 mb-2">Forgot Password?</h2>
            <p class="text-neutral-500">No worries, we'll send you reset instructions.</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-5">
                <label for="email" class="block text-neutral-700 text-sm font-medium mb-2">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-3 bg-neutral-900 text-white border-0 rounded-full focus:outline-none focus:ring-2 focus:ring-primary transition-all placeholder-neutral-500"
                       placeholder="johndoe@gmail.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-primary to-primary-hover hover:from-primary-hover hover:to-primary-active text-white font-semibold py-3 px-4 rounded-full transition-all btn-press shadow-lg hover:shadow-xl">
                Send Reset Link
            </button>
        </form>

        <p class="mt-6 text-center text-neutral-600">
            Remember your password? <a href="{{ route('login') }}" class="text-primary hover:text-primary-hover font-medium transition-colors">Back to login</a>
        </p>
    </div>
@endsection