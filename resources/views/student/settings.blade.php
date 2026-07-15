@extends('layouts.student')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
    <div class="mb-6">
        <a href="{{ route('student.dashboard') }}" class="text-primary hover:text-primary-hover font-medium text-sm">
            ← Back to Dashboard
        </a>
    </div>

    <div class="space-y-6">
        <!-- Account Settings -->
        <div class="bg-surface p-8 rounded-xl border border-neutral-200">
            <h3 class="text-xl font-semibold text-neutral-900 mb-6">Account Settings</h3>
            
            <form action="{{ route('student.settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-neutral-700 font-medium mb-2">Email Notifications</label>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="email_notifications" {{ old('email_notifications', $settings['email_notifications'] ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded border-neutral-300 text-primary focus:ring-primary">
                            <span class="text-sm text-neutral-700">Receive email notifications for funding request updates</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="milestone_reminders" {{ old('milestone_reminders', $settings['milestone_reminders'] ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded border-neutral-300 text-primary focus:ring-primary">
                            <span class="text-sm text-neutral-700">Receive milestone deadline reminders</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="newsletter" {{ old('newsletter', $settings['newsletter'] ?? false) ? 'checked' : '' }} class="w-4 h-4 rounded border-neutral-300 text-primary focus:ring-primary">
                            <span class="text-sm text-neutral-700">Subscribe to EduFund newsletter</span>
                        </label>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-neutral-700 font-medium mb-2">Privacy</label>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="profile_public" {{ old('profile_public', $settings['profile_public'] ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded border-neutral-300 text-primary focus:ring-primary">
                            <span class="text-sm text-neutral-700">Make my profile visible to donors</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-6 py-2.5 rounded-full font-semibold transition-all btn-press">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        <div class="bg-surface p-8 rounded-xl border border-red-200">
            <h3 class="text-xl font-semibold text-red-900 mb-4">Danger Zone</h3>
            <p class="text-sm text-neutral-600 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
            <button class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-full font-semibold transition-all">
                Delete Account
            </button>
        </div>
    </div>
@endsection