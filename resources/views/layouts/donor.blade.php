<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'EduFund') }} - @yield('title')</title>
    @include('partials.vite-assets')
</head>
<body class="bg-background min-h-screen flex">
    <x-sidebar.donor />
    <div class="flex-1 flex flex-col">
        <header class="bg-surface shadow-sm border-b border-neutral-200 p-4 flex items-center justify-between">
            <h1 class="text-xl font-semibold text-neutral-900">@yield('page-title')</h1>
            <div class="flex items-center gap-4">
                <span class="text-neutral-700">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded-full text-sm font-medium transition-all btn-press">Logout</button>
                </form>
            </div>
        </header>
        <main class="flex-1 p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
        <footer class="bg-neutral-900 text-neutral-400 text-center text-xs py-4 px-6">
            <div class="flex items-center justify-center gap-2 mb-1">
                <span class="badge-circle w-5 h-5 text-xs font-bold">E</span>
                <span class="font-semibold text-sm text-white">EduFund</span>
            </div>
            <p>&copy; {{ date('Y') }} EduFund Inc. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
