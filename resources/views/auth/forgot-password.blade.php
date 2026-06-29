@extends('layouts.auth')

@section('title', 'Forgot Password — ' . config('app.name', 'EduFund'))

@section('content')
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Forgot Password</h2>
    <p class="text-gray-600 text-sm mb-6 text-center">Enter your email and we'll send you a password reset link.</p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
            Send Password Reset Link
        </button>
    </form>

    <p class="mt-6 text-center text-gray-600">
        Remember your password? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
    </p>
@endsection
