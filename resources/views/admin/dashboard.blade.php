<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'EduFund') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex">
    <aside class="w-64 bg-white shadow-lg border-r border-gray-200">
        <div class="p-6">
            <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-blue-600">
                EduFund
            </a>
        </div>
        <nav class="px-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md bg-blue-50 text-blue-600">
                <span class="font-medium">Dashboard</span>
            </a>
        </nav>
    </aside>
    <div class="flex-1 flex flex-col">
        <header class="bg-white shadow-sm border-b border-gray-200 p-4 flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800">Admin Dashboard</h1>
            <div class="flex items-center gap-4">
                <span class="text-gray-600">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium">Logout</button>
                </form>
            </div>
        </header>
        <main class="flex-1 p-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Welcome, {{ auth()->user()->name }}</h3>
                <p class="text-gray-600 mb-2">Role: {{ ucfirst(auth()->user()->getRoleNames()->first()) }}</p>
            </div>
        </main>
    </div>
</body>
</html>
