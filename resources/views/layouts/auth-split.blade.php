<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'EduFund'))</title>
    @include('partials.vite-assets')
</head>
<body class="bg-background min-h-screen">
    <div class="min-h-screen flex">
        <!-- Left Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-background">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <a href="{{ route('login') }}" class="flex items-center gap-2 mb-8 text-2xl font-bold text-primary">
                    <span class="badge-circle w-9 h-9 text-lg">E</span>
                    {{ config('app.name', 'EduFund') }}
                </a>

                <!-- Status Messages -->
                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        <!-- Right Side - Testimonial/Info -->
        <div class="hidden lg:flex lg:w-1/2 bg-neutral-900 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-20 right-20 w-96 h-96 bg-primary rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 left-20 w-96 h-96 bg-primary rounded-full blur-3xl"></div>
            </div>

            <!-- Star Decoration -->
            <svg class="absolute right-20 top-1/2 -translate-y-1/2 w-64 h-64 text-primary opacity-40" viewBox="0 0 200 200" fill="none">
                <path d="M100 0L120 80L200 100L120 120L100 200L80 120L0 100L80 80L100 0Z" fill="currentColor"/>
            </svg>

            <div class="relative z-10 flex flex-col justify-center p-16 text-white">
                <h2 class="text-4xl font-bold mb-6 leading-tight">
                    @yield('testimonial-title', 'Transparent Education Funding')
                </h2>
                
                <div class="mb-8">
                    <svg class="w-12 h-12 text-primary mb-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4.995v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                    <p class="text-lg text-neutral-200 mb-8 leading-relaxed">
                        @yield('testimonial-text', 'EduFund leverages blockchain technology to ensure every donation is transparently tracked and directly supports students\' educational journey.')
                    </p>
                </div>

                <div>
                    <p class="font-semibold text-white">@yield('testimonial-name', 'EduFund Platform')</p>
                    <p class="text-neutral-400 text-sm">@yield('testimonial-role', 'Blockchain-Powered Education')</p>
                </div>

                <!-- Navigation Arrows for switching between Login and Register -->
                <div class="flex gap-3 mt-12">
                    <button onclick="switchTab('login')" class="w-12 h-12 rounded-full bg-primary/20 hover:bg-primary/30 flex items-center justify-center transition-all hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button onclick="switchTab('register')" class="w-12 h-12 rounded-full bg-primary hover:bg-primary-hover flex items-center justify-center transition-all hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global functions for tab switching
        window.showLogin = function() {
            const loginContainer = document.getElementById('login-form-container');
            const registerContainer = document.getElementById('register-form-container');
            const loginTab = document.getElementById('login-tab');
            const registerTab = document.getElementById('register-tab');
            const indicator = document.getElementById('tab-indicator');
            
            if (loginContainer && registerContainer) {
                loginContainer.classList.remove('hidden');
                registerContainer.classList.add('hidden');
                
                loginTab.classList.remove('text-neutral-500');
                loginTab.classList.add('text-neutral-900');
                registerTab.classList.remove('text-neutral-900');
                registerTab.classList.add('text-neutral-500');
                
                // Move indicator to login tab
                indicator.style.left = '4px';
                indicator.style.right = 'calc(50% - 4px)';
            }
        };

        window.showRegister = function() {
            const loginContainer = document.getElementById('login-form-container');
            const registerContainer = document.getElementById('register-form-container');
            const loginTab = document.getElementById('login-tab');
            const registerTab = document.getElementById('register-tab');
            const indicator = document.getElementById('tab-indicator');
            
            if (loginContainer && registerContainer) {
                loginContainer.classList.add('hidden');
                registerContainer.classList.remove('hidden');
                
                registerTab.classList.remove('text-neutral-500');
                registerTab.classList.add('text-neutral-900');
                loginTab.classList.remove('text-neutral-900');
                loginTab.classList.add('text-neutral-500');
                
                // Move indicator to register tab
                indicator.style.left = 'calc(50% + 4px)';
                indicator.style.right = '4px';
            }
        };

        window.switchTab = function(tab) {
            if (tab === 'login') {
                window.showLogin();
            } else if (tab === 'register') {
                window.showRegister();
            }
        };
    </script>
</body>
</html>
