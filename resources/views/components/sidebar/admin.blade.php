<aside class="w-64 bg-surface border-r border-neutral-200 min-h-screen flex flex-col">
    <div class="p-6 flex items-center gap-2">
        <span class="badge-circle w-8 h-8 text-base font-bold">E</span>
        <a href="{{ route('admin.dashboard') }}" class="font-bold text-lg text-neutral-900">
            {{ config('app.name', 'EduFund') }}
        </a>
    </div>
    <nav class="px-3 space-y-1 flex-1">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-primary-soft hover:text-primary rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary-soft text-primary' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('admin.schools.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-primary-soft hover:text-primary rounded-xl transition-colors {{ request()->routeIs('admin.schools.*') ? 'bg-primary-soft text-primary' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Schools
            @php
                $pendingCount = \App\Models\School::where('verification_status', 'pending')->count();
            @endphp
            @if($pendingCount > 0)
                <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.campaigns.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-primary-soft hover:text-primary rounded-xl transition-colors {{ request()->routeIs('admin.campaigns.*') ? 'bg-primary-soft text-primary' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
            </svg>
            Campaigns
        </a>

        <a href="{{ route('admin.transactions.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-primary-soft hover:text-primary rounded-xl transition-colors {{ request()->routeIs('admin.transactions.*') ? 'bg-primary-soft text-primary' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            Transactions
        </a>
    </nav>
</aside>