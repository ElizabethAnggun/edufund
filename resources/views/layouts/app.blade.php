<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'EduFund') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex">
    {{ $sidebar }}
    <div class="flex-1 flex flex-col">
        <header class="bg-white shadow-sm border-b border-gray-200 p-4 flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
            <div class="flex items-center gap-4">
                <span class="text-gray-600">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium">Logout</button>
                </form>
            </div>
        </header>
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
