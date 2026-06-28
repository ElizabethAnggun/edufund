<aside class="w-64 bg-white shadow-lg border-r border-gray-200">
    <div class="p-6">
        <a href="{{ route('student.dashboard') }}" class="text-2xl font-bold text-blue-600">
            {{ config('app.name', 'EduFund') }}
        </a>
    </div>
    <nav class="px-4 space-y-2">
        <a href="{{ route('student.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('student.dashboard') ? 'bg-blue-50 text-blue-600' : '' }}">
            <span class="font-medium">Dashboard</span>
        </a>
    </nav>
</aside>
