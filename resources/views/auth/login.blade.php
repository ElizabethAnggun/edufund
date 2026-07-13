@extends('layouts.auth-split')

@section('title', 'Login — ' . config('app.name', 'EduFund'))

@section('testimonial-title', 'Transparent Education Funding')
@section('testimonial-text', 'EduFund leverages blockchain technology to ensure every donation is transparently tracked and directly supports students\' educational journey.')
@section('testimonial-name', 'EduFund Platform')
@section('testimonial-role', 'Blockchain-Powered Education')

@section('content')
    <div class="animate-fade-in-up">
        <!-- Tab Navigation -->
        <div class="flex mb-8 bg-neutral-100 rounded-full p-1 relative">
            <div id="tab-indicator" class="absolute top-1 bottom-1 bg-white rounded-full shadow-sm transition-all duration-300" style="left: 4px; right: calc(50% - 4px);"></div>
            <button onclick="showLogin()" id="login-tab" class="flex-1 py-2.5 text-sm font-semibold text-neutral-900 relative z-10 transition-colors">Login</button>
            <button onclick="showRegister()" id="register-tab" class="flex-1 py-2.5 text-sm font-semibold text-neutral-500 relative z-10 transition-colors">Register</button>
        </div>

        <!-- Login Form -->
        <div id="login-form-container">
            <h2 class="text-3xl font-bold text-neutral-900 mb-2">Welcome back</h2>
            <p class="text-neutral-500 mb-8">Please enter your account details</p>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="mb-5">
                    <label for="email" class="block text-neutral-700 text-sm font-medium mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3 bg-neutral-900 text-white border-0 rounded-full focus:outline-none focus:ring-2 focus:ring-primary transition-all placeholder-neutral-500"
                           placeholder="johndoe@gmail.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-neutral-700 text-sm font-medium mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 bg-neutral-900 text-white border-0 rounded-full focus:outline-none focus:ring-2 focus:ring-primary transition-all"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-neutral-300 text-primary focus:ring-primary">
                        <span class="text-sm text-neutral-600">Keep me logged in</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-primary hover:text-primary-hover transition-colors underline">Forgot Password</a>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-primary to-primary-hover hover:from-primary-hover hover:to-primary-active text-white font-semibold py-3 px-4 rounded-full transition-all btn-press shadow-lg hover:shadow-xl">
                    Sign in
                </button>
            </form>
        </div>

        <!-- Register Form (Hidden by default) -->
        <div id="register-form-container" class="hidden">
            <h2 class="text-3xl font-bold text-neutral-900 mb-2">Create Account</h2>
            <p class="text-neutral-500 mb-8">Join EduFund and start your education journey</p>

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <div class="mb-5">
                    <label for="reg-name" class="block text-neutral-700 text-sm font-medium mb-2">Full Name</label>
                    <input type="text" id="reg-name" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 bg-neutral-900 text-white border-0 rounded-full focus:outline-none focus:ring-2 focus:ring-primary transition-all placeholder-neutral-500"
                           placeholder="John Doe">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="reg-email" class="block text-neutral-700 text-sm font-medium mb-2">Email</label>
                    <input type="email" id="reg-email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 bg-neutral-900 text-white border-0 rounded-full focus:outline-none focus:ring-2 focus:ring-primary transition-all placeholder-neutral-500"
                           placeholder="john@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="reg-password" class="block text-neutral-700 text-sm font-medium mb-2">Password</label>
                    <input type="password" id="reg-password" name="password" required
                           class="w-full px-4 py-3 bg-neutral-900 text-white border-0 rounded-full focus:outline-none focus:ring-2 focus:ring-primary transition-all"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="reg-password-confirmation" class="block text-neutral-700 text-sm font-medium mb-2">Confirm Password</label>
                    <input type="password" id="reg-password-confirmation" name="password_confirmation" required
                           class="w-full px-4 py-3 bg-neutral-900 text-white border-0 rounded-full focus:outline-none focus:ring-2 focus:ring-primary transition-all"
                           placeholder="••••••••">
                </div>

                <div class="mb-6">
                    <label for="role" class="block text-neutral-700 text-sm font-medium mb-2">I am a</label>
                    <select id="role" name="role" required
                            class="w-full px-4 py-3 bg-neutral-900 text-white border-0 rounded-full focus:outline-none focus:ring-2 focus:ring-primary transition-all">
                        <option value="">Select your role</option>
                        @foreach ([App\Enums\UserRole::STUDENT, App\Enums\UserRole::SCHOOL, App\Enums\UserRole::DONOR] as $role)
                            <option value="{{ $role->value }}" {{ old('role') === $role->value ? 'selected' : '' }}>
                                {{ ucfirst($role->value) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-primary to-primary-hover hover:from-primary-hover hover:to-primary-active text-white font-semibold py-3 px-4 rounded-full transition-all btn-press shadow-lg hover:shadow-xl">
                    Create Account
                </button>
            </form>
        </div>
    </div>

    <script>
        function showLogin() {
            document.getElementById('login-form-container').classList.remove('hidden');
            document.getElementById('register-form-container').classList.add('hidden');
            document.getElementById('login-tab').classList.remove('text-neutral-500');
            document.getElementById('login-tab').classList.add('text-neutral-900');
            document.getElementById('register-tab').classList.remove('text-neutral-900');
            document.getElementById('register-tab').classList.add('text-neutral-500');
            
            // Move indicator to login tab
            const indicator = document.getElementById('tab-indicator');
            indicator.style.left = '4px';
            indicator.style.right = 'calc(50% - 4px)';
        }

        function showRegister() {
            document.getElementById('login-form-container').classList.add('hidden');
            document.getElementById('register-form-container').classList.remove('hidden');
            document.getElementById('register-tab').classList.remove('text-neutral-500');
            document.getElementById('register-tab').classList.add('text-neutral-900');
            document.getElementById('login-tab').classList.remove('text-neutral-900');
            document.getElementById('login-tab').classList.add('text-neutral-500');
            
            // Move indicator to register tab
            const indicator = document.getElementById('tab-indicator');
            indicator.style.left = 'calc(50% + 4px)';
            indicator.style.right = '4px';
        }
    </script>
@endsection