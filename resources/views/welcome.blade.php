<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'EduFund') }} - Blockchain Education Funding</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-background text-neutral-900">
    <a href="#main-content" class="sr-only focus:not-sr-only">Skip to main content</a>

    <!-- Navigation -->
    <nav x-data="{ open: false }" class="glass fixed w-full z-50 top-0 backdrop-blur-xl bg-white/80 border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" class="flex items-center">
                    <img src="{{ asset('logo-edufund.png') }}" alt="EduFund" class="h-10 w-auto">
                </a>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#how-it-works" class="nav-link text-neutral-700 hover:text-primary transition">How It Works</a>
                    <a href="#scholarships" class="nav-link text-neutral-700 hover:text-primary transition">Scholarships</a>
                    <a href="{{ route('about') }}" class="nav-link text-neutral-700 hover:text-primary transition">About</a>
                </div>
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="nav-link">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Sign In</a>
                        <a href="{{ route('register') }}" class="btn-primary btn-press">Get Started</a>
                    @endauth
                </div>
                <button @click="open=!open" class="md:hidden p-2 rounded-lg hover:bg-neutral-100">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div x-show="open" x-transition x-cloak class="md:hidden border-t py-4 space-y-4">
                <a href="#how-it-works" @click="open=false" class="block nav-link">How It Works</a>
                <a href="#scholarships" @click="open=false" class="block nav-link">Scholarships</a>
                <a href="{{ route('about') }}" @click="open=false" class="block nav-link">About</a>
                <hr>
                @auth
                    <a href="{{ url('/dashboard') }}" class="block nav-link">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="block nav-link">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block nav-link">Sign In</a>
                    <a href="{{ route('register') }}" class="block btn-primary text-center">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <main id="main-content">
        <!-- Hero Section -->
        <section id="hero" class="relative overflow-hidden bg-background pt-32 pb-28">
            <div class="absolute inset-0 hero-bg"></div>
            <div class="absolute inset-0 opacity-30 pointer-events-none">
                <div class="absolute -top-48 -left-40 w-[700px] h-[700px] rounded-full bg-primary/10 blur-3xl"></div>
                <div class="absolute bottom-0 right-0 w-[550px] h-[550px] rounded-full bg-primary/10 blur-3xl"></div>
            </div>
            <div class="relative z-10 max-w-7xl mx-auto px-6">
                <div class="grid lg:grid-cols-2 gap-20 items-center">
                    <div class="relative z-20">
                        <h1 class="text-5xl lg:text-7xl font-black leading-[1.05] tracking-tight mb-8">
                            Empowering Dreams Through
                            <span class="block text-gradient">Scholarships</span>
                        </h1>
                        <p class="text-lg lg:text-xl text-neutral-500 leading-9 max-w-xl mb-10">
                            EduFund helps students receive transparent scholarship
                            funding through blockchain technology while allowing donors
                            to monitor every contribution securely.
                        </p>
                        <div class="flex flex-wrap gap-5 mb-12">
                            <a href="{{ route('register') }}" class="btn-primary px-8 py-4 text-lg">Start Scholarship</a>
                            <a href="#how-it-works" class="btn-outline bg-white px-8 py-4 text-lg">Learn More</a>
                        </div>
                        <div class="flex gap-12">
                            <div>
                                <h3 class="text-4xl font-bold text-primary">1200+</h3>
                                <p class="text-neutral-500">Students</p>
                            </div>
                            <div>
                                <h3 class="text-4xl font-bold text-primary">$1.4M</h3>
                                <p class="text-neutral-500">Distributed</p>
                            </div>
                            <div>
                                <h3 class="text-4xl font-bold text-primary">100%</h3>
                                <p class="text-neutral-500">Transparent</p>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex justify-center">
                        <div class="absolute -top-20 left-1/2 -translate-x-1/2 z-30 text-center">
                            <div class="relative">
                                <img src="{{ asset('images/student.png') }}"
                                    onerror="this.src='https://ui-avatars.com/api/?name=Sarah+Johnson&background=2B6CB0&color=fff'"
                                    class="w-40 h-40 rounded-full border-8 border-white shadow-2xl object-cover">
                            </div>
                            <h3 class="mt-5 text-2xl font-bold text-neutral-900">Sarah Johnson</h3>
                            <p class="text-neutral-500">Computer Science</p>
                        </div>
                        <div class="relative w-full max-w-xl bg-white rounded-[36px] shadow-2xl px-8 pt-40 pb-10 z-10">
                            <div>
                                <div class="flex justify-between items-center mb-3">
                                    <span class="font-semibold text-lg">Funding Progress</span>
                                    <span class="font-bold text-primary">78%</span>
                                </div>
                                <div class="h-3 bg-neutral-200 rounded-full overflow-hidden">
                                    <div class="progress-fill h-full rounded-full bg-gradient-to-r from-primary to-primary-active" style="width:78%" data-fill="78%"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-5 mt-10">
                                <div class="bg-neutral-100 rounded-2xl p-5">
                                    <p class="text-neutral-500">Raised</p>
                                    <h3 class="text-4xl font-bold mt-2">$3,900</h3>
                                </div>
                                <div class="bg-neutral-100 rounded-2xl p-5">
                                    <p class="text-neutral-500">Supporters</p>
                                    <h3 class="text-4xl font-bold mt-2">42</h3>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -left-8 top-10 bg-white rounded-2xl shadow-xl px-6 py-5 z-40">
                            <p class="text-sm text-neutral-500">Verified</p>
                            <p class="font-bold text-primary text-lg">Blockchain</p>
                        </div>
                        <div class="absolute -right-8 bottom-8 bg-primary text-white rounded-3xl shadow-2xl px-8 py-6 z-40">
                            <p class="text-sm opacity-90">Total Donated</p>
                            <h3 class="text-4xl font-bold">$1.4M</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 animate-bounce">
                <svg class="w-7 h-7 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="relative py-28 bg-background overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[700px] h-[300px] bg-primary/10 blur-3xl rounded-full pointer-events-none"></div>
            <div class="relative max-w-7xl mx-auto px-6">
                <div class="text-center max-w-3xl mx-auto mb-20 reveal-item">
                    <p class="uppercase tracking-[0.3em] text-sm font-semibold text-primary mb-4">Why EduFund</p>
                    <h2 class="text-4xl lg:text-5xl font-black text-neutral-900 leading-tight">
                        Reasons to pursue
                        <span class="block text-gradient">EduFund Scholarships</span>
                    </h2>
                    <p class="mt-6 text-lg text-neutral-500 leading-8">
                        A transparent blockchain-based scholarship system connecting students, institutions, and donors.
                    </p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="reveal-item group relative bg-white rounded-[32px] p-8 shadow-sm hover:shadow-xl border border-neutral-100 transition-all duration-300 hover:-translate-y-2">
                        <div class="absolute top-5 right-6 text-7xl font-black text-primary/10">01</div>
                        <div class="relative w-20 h-20 rounded-3xl bg-primary/10 flex items-center justify-center mb-8 group-hover:bg-primary transition">
                            <svg class="w-10 h-10 text-primary group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.053-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-neutral-900 mb-4">Transparent Funding</h3>
                        <p class="text-neutral-500 leading-7 text-sm">Every donation is recorded on-chain, providing complete visibility and trust.</p>
                    </div>
                    <div class="reveal-item group relative bg-white rounded-[32px] p-8 shadow-sm hover:shadow-xl border border-neutral-100 transition-all duration-300 hover:-translate-y-2">
                        <div class="absolute top-5 right-6 text-7xl font-black text-primary/10">02</div>
                        <div class="relative w-20 h-20 rounded-3xl bg-primary/10 flex items-center justify-center mb-8 group-hover:bg-primary transition">
                            <svg class="w-10 h-10 text-primary group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-neutral-900 mb-4">Secure Escrow</h3>
                        <p class="text-neutral-500 leading-7 text-sm">Smart contracts securely hold funds until verified milestones are achieved.</p>
                    </div>
                    <div class="reveal-item group relative bg-white rounded-[32px] p-8 shadow-sm hover:shadow-xl border border-neutral-100 transition-all duration-300 hover:-translate-y-2">
                        <div class="absolute top-5 right-6 text-7xl font-black text-primary/10">03</div>
                        <div class="relative w-20 h-20 rounded-3xl bg-primary/10 flex items-center justify-center mb-8 group-hover:bg-primary transition">
                            <svg class="w-10 h-10 text-primary group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-neutral-900 mb-4">Verified Progress</h3>
                        <p class="text-neutral-500 leading-7 text-sm">Academic milestones are verified before funds are released.</p>
                    </div>
                    <div class="reveal-item group relative bg-white rounded-[32px] p-8 shadow-sm hover:shadow-xl border border-neutral-100 transition-all duration-300 hover:-translate-y-2">
                        <div class="absolute top-5 right-6 text-7xl font-black text-primary/10">04</div>
                        <div class="relative w-20 h-20 rounded-3xl bg-primary/10 flex items-center justify-center mb-8 group-hover:bg-primary transition">
                            <svg class="w-10 h-10 text-primary group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-neutral-900 mb-4">Instant Release</h3>
                        <p class="text-neutral-500 leading-7 text-sm">Automated blockchain payments remove delays and unnecessary bureaucracy.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trusted Partners -->
        <section class="section-spacing bg-background border-y border-neutral-200 overflow-hidden">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-10 reveal-item">
                    <p class="text-xs uppercase tracking-[0.35em] text-neutral-500 font-semibold mb-4">TRUSTED PARTNERS</p>
                    <h2 class="text-3xl md:text-4xl font-bold text-neutral-900">
                        Backed by leading
                        <span class="text-gradient">education networks</span>
                    </h2>
                </div>
                <div class="relative">
                    <div class="absolute inset-0 bg-primary/5 blur-3xl rounded-full"></div>
                    <div class="relative flex flex-wrap justify-center items-center gap-x-16 gap-y-8">
                        <span class="reveal-item text-2xl md:text-3xl font-black tracking-tight text-neutral-500 hover:text-neutral-900 transition">EduTrust</span>
                        <span class="reveal-item text-2xl md:text-3xl font-black tracking-tight text-neutral-500 hover:text-neutral-900 transition">ScholarNet</span>
                        <span class="reveal-item text-2xl md:text-3xl font-black tracking-tight text-neutral-500 hover:text-neutral-900 transition">BlockEd</span>
                        <span class="reveal-item text-2xl md:text-3xl font-black tracking-tight text-neutral-500 hover:text-neutral-900 transition">LearnDAO</span>
                        <span class="reveal-item text-2xl md:text-3xl font-black tracking-tight text-neutral-500 hover:text-neutral-900 transition">Soroban</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Active Scholarships -->
        <section id="scholarships" class="pt-8 pb-20 bg-background">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex justify-between items-end mb-8">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-neutral-900">Active Scholarships</h2>
                        <p class="text-neutral-500 mt-2">Verified opportunities awaiting your support</p>
                    </div>
                    <a href="{{ route('register') }}" class="hidden md:flex items-center gap-2 text-primary font-semibold hover:text-primary-hover transition">
                        View All
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="reveal-item card card-enhanced rounded-3xl p-6 hover:-translate-y-1 transition-all duration-300">
                        <div class="flex justify-between items-start mb-5">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-primary to-primary-active flex items-center justify-center text-white font-bold text-lg shadow-lg">SJ</div>
                                <div>
                                    <h3 class="font-bold text-neutral-900 text-lg">Sarah Johnson</h3>
                                    <p class="text-sm text-neutral-500">B.Sc. Computer Science</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-primary-soft text-primary text-xs font-semibold">Active</span>
                        </div>
                        <p class="text-sm text-neutral-500 leading-relaxed mb-5">Help me complete my final year in CS. Building accessible education technology for rural communities.</p>
                        <div class="mb-5">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-neutral-500">Scholarship Goal</span>
                                <span class="font-bold">$1,450 / $5,000</span>
                            </div>
                            <div class="h-2 bg-neutral-100 rounded-full overflow-hidden">
                                <div class="progress-fill h-full rounded-full bg-gradient-to-r from-primary to-primary-active" style="width:29%" data-fill="29%"></div>
                            </div>
                        </div>
                        <div class="flex justify-between text-xs text-neutral-500">
                            <span>42 Supporters</span>
                            <span>3/6 Milestones</span>
                        </div>
                    </div>
                    <div class="reveal-item card card-enhanced rounded-3xl p-6 hover:-translate-y-1 transition-all duration-300">
                        <div class="flex justify-between items-start mb-5">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-neutral-700 to-neutral-900 flex items-center justify-center text-white font-bold text-lg shadow-lg">MC</div>
                                <div>
                                    <h3 class="font-bold text-neutral-900 text-lg">Marcus Chen</h3>
                                    <p class="text-sm text-neutral-500">M.Sc. Data Science</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-neutral-900 text-white text-xs font-semibold">Ending Soon</span>
                        </div>
                        <p class="text-sm text-neutral-500 leading-relaxed mb-5">Funding my master's thesis on AI-driven climate modeling and critical research.</p>
                        <div class="mb-5">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-neutral-500">Scholarship Goal</span>
                                <span class="font-bold">$2,200 / $8,000</span>
                            </div>
                            <div class="h-2 bg-neutral-100 rounded-full overflow-hidden">
                                <div class="progress-fill h-full rounded-full bg-gradient-to-r from-primary to-primary-active" style="width:27%" data-fill="27%"></div>
                            </div>
                        </div>
                        <div class="flex justify-between text-xs text-neutral-500">
                            <span>18 Supporters</span>
                            <span>1/4 Milestones</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="relative py-24 overflow-hidden bg-primary">
            <div class="absolute inset-0">
                <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-white/10 rounded-full blur-3xl"></div>
            </div>
            <div class="relative max-w-5xl mx-auto px-6 text-center">
                <div class="reveal-item">
                    <p class="text-sm uppercase tracking-[0.3em] text-white/70 font-semibold mb-5">MAKE AN IMPACT</p>
                    <h2 class="text-4xl md:text-6xl font-black text-white leading-tight mb-6">Fund the next generation of innovators</h2>
                    <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto leading-relaxed mb-10">
                        Support verified students through transparent blockchain-powered scholarships and help transform ambitious dreams into reality.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('register') }}" class="bg-white text-primary px-9 py-4 rounded-full font-bold text-lg shadow-xl hover:-translate-y-1 transition">Start a Scholarship</a>
                        <a href="{{ route('login') }}" class="border-2 border-white/40 text-white px-9 py-4 rounded-full font-bold text-lg hover:bg-white/10 transition">Become a Donor</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-neutral-900 text-white">
        <div class="max-w-7xl mx-auto px-6 py-16">
            <div class="grid md:grid-cols-4 gap-10">
                <div class="md:col-span-2">
                    <img src="{{ asset('logo-edufund.png') }}" class="h-12 mb-6">
                    <p class="text-neutral-500 max-w-md leading-relaxed">EduFund connects students and donors through transparent blockchain-based scholarship funding.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-5">Platform</h4>
                    <ul class="space-y-3 text-neutral-500 text-sm">
                        <li><a href="#scholarships" class="hover:text-white transition">Scholarships</a></li>
                        <li><a href="#how-it-works" class="hover:text-white transition">How It Works</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">For Donors</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-5">Company</h4>
                    <ul class="space-y-3 text-neutral-500 text-sm">
                        <li><a href="{{ route('about') }}" class="hover:text-white transition">About</a></li>
                        <li><a href="{{ route('about') }}#contact" class="hover:text-white transition">Contact</a></li>
                        <li><a href="#" @click.prevent="$dispatch('open-privacy')" class="hover:text-white transition">Privacy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 mt-12 pt-8 flex flex-col md:flex-row justify-between gap-4">
                <p class="text-sm text-neutral-500">© {{ date('Y') }} EduFund Inc. All rights reserved.</p>
                <p class="text-sm text-neutral-500">Powered by blockchain technology</p>
            </div>
        </div>
    </footer>

    <!-- Privacy Modal -->
    <div x-data="{ privacyOpen: false }"
         x-on:open-privacy.window="privacyOpen = true"
         x-on:close-privacy.window="privacyOpen = false"
         x-cloak>
        <div x-show="privacyOpen"
             x-transition.opacity
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
            <div x-show="privacyOpen"
                 x-transition
                 @click.outside="privacyOpen = false"
                 class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[80vh] overflow-y-auto p-8">
                <button @click="privacyOpen = false" class="absolute top-4 right-4 text-neutral-500 hover:text-neutral-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h3 class="text-2xl font-bold text-neutral-900 mb-4">Privacy Policy</h3>
                <div class="space-y-4 text-neutral-500 text-sm leading-relaxed">
                    <p>EduFund respects your privacy. This policy outlines how we collect, use, and protect your personal data.</p>
                    <h4 class="font-bold text-neutral-900">Data We Collect</h4>
                    <p>We collect information you provide during registration, including your name, email address, and educational background.</p>
                    <h4 class="font-bold text-neutral-900">How We Use Data</h4>
                    <p>Your data is used solely to facilitate scholarship funding, verify academic progress, and improve our platform.</p>
                    <h4 class="font-bold text-neutral-900">Data Security</h4>
                    <p>All data is encrypted and stored securely. Blockchain transactions are public by design, but personal identity is kept confidential.</p>
                    <h4 class="font-bold text-neutral-900">Contact</h4>
                    <p>If you have any questions, please <a href="{{ route('about') }}#contact" class="text-primary hover:underline">contact us</a>.</p>
                </div>
                <button @click="privacyOpen = false" class="mt-6 btn-primary px-6 py-3 w-full text-center">Close</button>
            </div>
        </div>
    </div>
</body>
</html>
