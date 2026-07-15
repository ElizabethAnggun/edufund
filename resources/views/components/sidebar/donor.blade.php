<aside class="w-64 bg-surface border-r border-neutral-200 min-h-screen flex flex-col">
    <div class="p-6 flex items-center gap-2">
        <span class="badge-circle w-8 h-8 text-base font-bold">E</span>
        <a href="{{ route('donor.dashboard') }}" class="font-bold text-lg text-neutral-900">
            {{ config('app.name', 'EduFund') }}
        </a>
    </div>
    <nav class="px-3 space-y-1 flex-1">
        <a href="{{ route('donor.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-primary-soft hover:text-primary rounded-xl transition-colors {{ request()->routeIs('donor.dashboard') ? 'bg-primary-soft text-primary' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('donor.campaigns.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-primary-soft hover:text-primary rounded-xl transition-colors {{ request()->routeIs('donor.campaigns.index') || request()->routeIs('donor.campaigns.show') ? 'bg-primary-soft text-primary' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Browse Campaigns
        </a>

        <a href="{{ route('donor.saved-campaigns.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-primary-soft hover:text-primary rounded-xl transition-colors {{ request()->routeIs('donor.saved-campaigns.*') ? 'bg-primary-soft text-primary' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
            </svg>
            Saved Campaigns
        </a>

        <a href="{{ route('donor.donations.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-primary-soft hover:text-primary rounded-xl transition-colors {{ request()->routeIs('donor.donations.*') ? 'bg-primary-soft text-primary' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Donation History
        </a>

        <a href="{{ route('donor.wallet') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-primary-soft hover:text-primary rounded-xl transition-colors {{ request()->routeIs('donor.wallet') ? 'bg-primary-soft text-primary' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Stellar Wallet
        </a>

        <a href="{{ route('donor.notifications.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-primary-soft hover:text-primary rounded-xl transition-colors {{ request()->routeIs('donor.notifications.*') ? 'bg-primary-soft text-primary' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            Notifications
        </a>

        <a href="{{ route('donor.settings') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-primary-soft hover:text-primary rounded-xl transition-colors {{ request()->routeIs('donor.settings') ? 'bg-primary-soft text-primary' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Settings
        </a>
    </nav>
</aside>