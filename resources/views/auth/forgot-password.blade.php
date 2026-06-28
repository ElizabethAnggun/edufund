<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'EduFund') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
        <a href="{{ route('login') }}" class="flex items-center justify-center mb-6 text-2xl font-bold text-blue-600">
            EduFund
        </a>
        <div>
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                    {{ session('status') }}
                </div>
            @endif
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
        </div>
    </div>
</body>
</html>
