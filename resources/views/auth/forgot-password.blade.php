@extends('layouts.auth')

@section('title', 'Forgot Password — ' . config('app.name', 'EduFund'))

@section('content')
    <h2 class="text-2xl font-bold text-center text-neutral-900 mb-6">Forgot Password</h2>
    <p class="text-neutral-600 text-sm mb-6 text-center">Enter your email and we'll send you a password reset link.</p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-neutral-700 text-sm font-medium mb-2">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
            @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white font-semibold py-2.5 px-4 rounded-full transition-all btn-press">
            Send Password Reset Link
        </button>
    </form>

    <p class="mt-6 text-center text-neutral-600">
        Remember your password? <a href="{{ route('login') }}" class="text-primary hover:text-primary-hover font-medium transition-colors">Login</a>
    </p>
@endsection
