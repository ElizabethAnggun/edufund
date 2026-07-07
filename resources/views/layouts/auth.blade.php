<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'EduFund'))</title>
    @include('partials.vite-assets')
</head>
<body class="bg-background min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md p-8 bg-surface rounded-2xl shadow-lg border border-neutral-200">
        <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 mb-6 text-2xl font-bold text-primary">
            <span class="badge-circle w-9 h-9 text-lg">E</span>
            {{ config('app.name', 'EduFund') }}
        </a>

        @if (session('status'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl">
                {{ session('status') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
