@extends('layouts.auth')

@section('title', 'Login — ' . config('app.name', 'EduFund'))

@section('content')
    <h2 class="text-2xl font-bold text-center text-neutral-900 mb-6">Login</h2>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-neutral-700 text-sm font-medium mb-2">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
            @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-neutral-700 text-sm font-medium mb-2">Password</label>
            <input type="password" id="password" name="password" required
                   class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
            @error('password')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4 flex items-center justify-between">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="remember" class="rounded border-neutral-300 text-primary focus:ring-primary">
                <span class="text-sm text-neutral-600">Remember me</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-primary hover:text-primary-hover transition-colors">Forgot password?</a>
        </div>

        <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white font-semibold py-2.5 px-4 rounded-full transition-all btn-press">
            Login
        </button>
    </form>

    <p class="mt-6 text-center text-neutral-600">
        Don't have an account? <a href="{{ route('register') }}" class="text-primary hover:text-primary-hover font-medium transition-colors">Register</a>
    </p>
@endsection
