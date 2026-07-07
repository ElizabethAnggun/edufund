@extends('layouts.auth')

@section('title', 'Reset Password — ' . config('app.name', 'EduFund'))

@section('content')
    <h2 class="text-2xl font-bold text-center text-neutral-900 mb-6">Reset Password</h2>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-4">
            <label for="email" class="block text-neutral-700 text-sm font-medium mb-2">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', request()->email) }}" required autofocus
                   class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
            @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-neutral-700 text-sm font-medium mb-2">New Password</label>
            <input type="password" id="password" name="password" required
                   class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
            @error('password')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-neutral-700 text-sm font-medium mb-2">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                   class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
        </div>

        <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white font-semibold py-2.5 px-4 rounded-full transition-all btn-press">
            Reset Password
        </button>
    </form>
@endsection
