<x-layouts.app :title="'Donor Dashboard'">
    <x-slot:sidebar>
        <x-sidebar.donor />
    </x-slot:sidebar>
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Welcome, {{ auth()->user()->name }}</h3>
        <p class="text-gray-600 mb-2">Role: {{ ucfirst(auth()->user()->getRoleNames()->first()) }}</p>
    </div>
</x-layouts.app>
