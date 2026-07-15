<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'EduFund') }} - About Us</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Translate Widget -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
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
                    <a href="/#how-it-works" class="nav-link text-neutral-700 hover:text-primary transition">How It Works</a>
                    <a href="/#scholarships" class="nav-link text-neutral-700 hover:text-primary transition">Scholarships</a>
                    <a href="{{ route('about') }}" class="nav-link text-primary font-semibold transition">About</a>
                </div>
                <div class="hidden md:flex items-center gap-4">
                    <div id="google_translate_element" class="text-sm"></div>
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
                <a href="/#how-it-works" @click="open=false" class="block nav-link">How It Works</a>
                <a href="/#scholarships" @click="open=false" class="block nav-link">Scholarships</a>
                <a href="{{ route('about') }}" @click="open=false" class="block nav-link text-primary">About</a>
                <hr>
                <div class="pb-4">
                    <div id="google_translate_element_mobile" class="text-sm"></div>
                </div>
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
        <!-- About Hero -->
        <section class="relative pt-36 pb-28 bg-background overflow-hidden">
            <div class="absolute inset-0 opacity-30 pointer-events-none">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] rounded-full bg-primary/10 blur-3xl"></div>
            </div>
            <div class="relative max-w-4xl mx-auto px-6 text-center">
                <p class="uppercase tracking-[0.3em] text-sm font-semibold text-primary mb-4">About EduFund</p>
                <h1 class="text-5xl lg:text-7xl font-black text-neutral-900 leading-tight mb-8">
                    Bridging dreams with
                    <span class="block text-gradient">blockchain transparency</span>
                </h1>
                <p class="text-lg text-neutral-500 leading-relaxed max-w-2xl mx-auto">
                    We believe every student deserves equal access to education funding. EduFund leverages blockchain technology to create a transparent, secure, and fair scholarship ecosystem.
                </p>
            </div>
        </section>

        <!-- Mission & Vision -->
        <section class="py-20 bg-background border-y border-neutral-200">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid md:grid-cols-2 gap-12">
                    <div class="bg-white rounded-[32px] p-10 shadow-sm border border-neutral-100">
                        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-neutral-900 mb-4">Our Mission</h2>
                        <p class="text-neutral-500 leading-relaxed">Empower every student to access fair and transparent education funding through decentralized technology, removing barriers and building trust between donors and recipients.</p>
                    </div>
                    <div class="bg-white rounded-[32px] p-10 shadow-sm border border-neutral-100">
                        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-neutral-900 mb-4">Our Vision</h2>
                        <p class="text-neutral-500 leading-relaxed">A world where education funding is borderless, trustless, and accessible to all — powered by smart contracts and community collaboration.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Values -->
        <section class="py-20 bg-background">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl lg:text-5xl font-black text-neutral-900 leading-tight">
                        What we
                        <span class="text-gradient">stand for</span>
                    </h2>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.053-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-neutral-900 mb-3">Transparency</h3>
                        <p class="text-neutral-500 text-sm leading-relaxed">Every transaction is recorded on the blockchain, visible to all parties with no hidden fees.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-neutral-900 mb-3">Security</h3>
                        <p class="text-neutral-500 text-sm leading-relaxed">Smart contracts ensure funds are only released when academic milestones are verified.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-neutral-900 mb-3">Community</h3>
                        <p class="text-neutral-500 text-sm leading-relaxed">Donors, students, and institutions collaborate in a trusted ecosystem built on integrity.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-20 bg-background border-t border-neutral-200">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid md:grid-cols-2 gap-16 items-center">
                    <div>
                        <p class="uppercase tracking-[0.3em] text-sm font-semibold text-primary mb-4">Get in Touch</p>
                        <h2 class="text-4xl lg:text-5xl font-black text-neutral-900 leading-tight mb-6">
                            Let's build the future
                            <span class="block text-gradient">of education funding</span>
                        </h2>
                        <p class="text-neutral-500 leading-relaxed mb-8">
                            Have questions, partnership ideas, or feedback? We'd love to hear from you.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-neutral-900">Email</p>
                                    <p class="text-sm text-neutral-500">hello@edufund.io</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-neutral-900">Location</p>
                                    <p class="text-sm text-neutral-500">Jakarta, Indonesia</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-[32px] p-8 shadow-sm border border-neutral-100">
                        <form class="space-y-5">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-neutral-900 mb-2">Name</label>
                                <input type="text" id="name" placeholder="Your name" class="w-full px-4 py-3 rounded-xl border border-neutral-200 bg-background text-neutral-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-neutral-900 mb-2">Email</label>
                                <input type="email" id="email" placeholder="you@example.com" class="w-full px-4 py-3 rounded-xl border border-neutral-200 bg-background text-neutral-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-semibold text-neutral-900 mb-2">Message</label>
                                <textarea id="message" rows="4" placeholder="Tell us more..." class="w-full px-4 py-3 rounded-xl border border-neutral-200 bg-background text-neutral-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition resize-none"></textarea>
                            </div>
                            <button type="submit" class="btn-primary w-full py-3 text-center">Send Message</button>
                        </form>
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
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-white/70 font-semibold mb-5">Join Us</p>
                    <h2 class="text-4xl md:text-6xl font-black text-white leading-tight mb-6">Ready to make a difference?</h2>
                    <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto leading-relaxed mb-10">
                        Start your scholarship journey or become a donor today.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('register') }}" class="bg-white text-primary px-9 py-4 rounded-full font-bold text-lg shadow-xl hover:-translate-y-1 transition">Get Started</a>
                        <a href="{{ route('login') }}" class="border-2 border-white/40 text-white px-9 py-4 rounded-full font-bold text-lg hover:bg-white/10 transition">Sign In</a>
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
                        <li><a href="/#scholarships" class="hover:text-white transition">Scholarships</a></li>
                        <li><a href="/#how-it-works" class="hover:text-white transition">How It Works</a></li>
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
