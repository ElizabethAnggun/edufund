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
            {{ config('app.name', 'EduFund') }}
        </a>
        <div>
            {{ $slot }}
        </div>
    </div>
</body>
</html>
