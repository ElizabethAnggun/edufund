<aside class="w-64 bg-surface border-r border-neutral-200 min-h-screen">
    <div class="p-6 flex items-center gap-2">
        <span class="badge-circle w-8 h-8 text-base font-bold">E</span>
        <a href="{{ route('student.dashboard') }}" class="font-bold text-lg text-neutral-900">
            {{ config('app.name', 'EduFund') }}
        </a>
    </div>
    <nav class="px-3 space-y-1">
        <a href="{{ route('student.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-primary-soft hover:text-primary rounded-xl transition-colors {{ request()->routeIs('student.dashboard') ? 'bg-primary-soft text-primary' : '' }}">
            Dashboard
        </a>
        <a href="{{ route('student.profile') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-primary-soft hover:text-primary rounded-xl transition-colors {{ request()->routeIs('student.profile') ? 'bg-primary-soft text-primary' : '' }}">
            Profile
        </a>
        <a href="{{ route('student.funding-requests.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-primary-soft hover:text-primary rounded-xl transition-colors {{ request()->routeIs('student.funding-requests.*') ? 'bg-primary-soft text-primary' : '' }}">
            Funding Requests
        </a>
    </nav>
</aside>
