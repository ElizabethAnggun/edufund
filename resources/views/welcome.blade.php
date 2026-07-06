<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="EduFund is a blockchain-powered education funding platform connecting students, schools, and donors through transparent milestone-based scholarships on the Stellar Network.">
    <meta name="theme-color" content="#FFFFFF">
    <title>{{ config('app.name', 'EduFund') }} - Blockchain Education Funding</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        @keyframes float { 
            0%, 100% { transform: translateY(0px); } 
            50% { transform: translateY(-10px); } 
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans bg-white text-gray-900 antialiased overflow-x-hidden selection:bg-[#E25B24] selection:text-white">

<a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[60] focus:px-4 focus:py-2 focus:bg-white focus:text-[#E25B24] focus:border focus:border-gray-200 focus:rounded-lg focus:text-sm focus:font-semibold shadow-sm">
    Skip to main content
</a>

<div x-data="{ mobileOpen: false }">
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <a href="{{ url('/') }}" class="flex items-center shrink-0 gap-2.5 group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#E25B24] to-[#F28C28] flex items-center justify-center text-white font-bold text-lg shadow-sm transition-transform duration-300 group-hover:scale-105">
                        E
                    </div>
                    <span class="text-xl font-bold tracking-tight text-gray-900">Edu<span class="text-[#E25B24]">Fund</span></span>
                </a>

                <nav class="hidden lg:flex items-center gap-10" aria-label="Main navigation">
                    <a href="#how-it-works" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">How It Works</a>
                    <a href="#features" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">Features</a>
                    <a href="#campaigns" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">Campaigns</a>
                </nav>

                <div class="hidden lg:flex items-center gap-6">
                    <div id="google_translate_element"></div>
                    
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Dashboard</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors pointer-events-auto">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">Sign in</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-[18px] py-2.5 text-sm font-semibold text-white bg-gradient-to-br from-[#E25B24] to-[#F28C28] rounded-xl hover:shadow-lg hover:shadow-[#E25B24]/20 transition-all duration-300 transform hover:-translate-y-0.5">
                            Get Started
                        </a>
                    @endauth
                </div>

                <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 rounded-xl hover:bg-gray-50 transition-colors text-gray-600" aria-label="Toggle menu">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        <div x-show="mobileOpen" x-cloak x-transition class="lg:hidden border-t border-gray-100 bg-white shadow-xl">
            <div class="px-4 py-6 space-y-2">
                <a href="#how-it-works" @click="mobileOpen = false" class="block px-3 py-2 rounded-xl text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900">How It Works</a>
                <a href="#features" @click="mobileOpen = false" class="block px-3 py-2 rounded-xl text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900">Features</a>
                <a href="#campaigns" @click="mobileOpen = false" class="block px-3 py-2 rounded-xl text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900">Campaigns</a>
                <div class="pt-4 mt-4 border-t border-gray-100">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="block px-3 py-2 text-base font-semibold text-[#E25B24]">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 rounded-xl">Sign in</a>
                        <a href="{{ route('register') }}" class="mt-2 block w-full text-center px-5 py-3 text-sm font-semibold text-white bg-gradient-to-br from-[#E25B24] to-[#F28C28] rounded-xl shadow-sm">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main id="main-content">
        <section class="relative pt-32 pb-20 lg:pt-44 lg:pb-32 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-[#FFFBF7]/50 via-white to-white pointer-events-none"></div>
            
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                    
                    <div class="lg:col-span-6 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-white border border-gray-200 rounded-full text-xs font-semibold text-gray-600 mb-6 shadow-sm">
                            <span class="flex h-2 w-2 relative">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#E25B24] opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-[#E25B24]"></span>
                            </span>
                            Live on the Stellar Network
                        </div>
                        
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-gray-900 leading-[1.08] tracking-tight mb-6">
                            Education <br class="hidden lg:block" />
                            <span class="text-transparent bg-clip-text bg-gradient-to-br from-[#E25B24] to-[#F28C28]">Funded Transparently.</span>
                        </h1>
                        
                        <p class="text-base sm:text-lg text-gray-500 max-w-[520px] mx-auto lg:mx-0 mb-10 leading-relaxed">
                            EduFund connects students, schools, and donors through verifiable milestone-based scholarships. Every contribution is securely routed and recorded on-chain.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start mb-12">
                            <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 text-sm font-bold text-white bg-gradient-to-br from-[#E25B24] to-[#F28C28] rounded-xl hover:shadow-xl hover:shadow-[#E25B24]/20 hover:-translate-y-0.5 transition-all duration-300">
                                Start Campaign
                                <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                            <a href="#how-it-works" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-300">
                                How It Works
                            </a>
                        </div>

                        <div class="grid grid-cols-3 gap-4 border-t border-gray-100 pt-8 max-w-[440px] mx-auto lg:mx-0 text-left">
                            <div>
                                <div class="text-xl sm:text-2xl font-bold text-gray-900">$1.4M+</div>
                                <div class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">On-chain Raised</div>
                            </div>
                            <div>
                                <div class="text-xl sm:text-2xl font-bold text-gray-900">1,200+</div>
                                <div class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Students Supported</div>
                            </div>
                            <div>
                                <div class="text-xl sm:text-2xl font-bold text-[#E25B24]">98%</div>
                                <div class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Milestone Rate</div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-6 w-full max-w-[560px] mx-auto lg:max-w-none relative">
                        <div class="absolute inset-0 bg-gradient-to-tr from-[#E25B24]/5 to-[#8B5CF6]/5 rounded-[2rem] transform rotate-2 scale-105 pointer-events-none"></div>
                        
                        <div class="relative bg-white border border-gray-200 rounded-2xl shadow-2xl shadow-gray-200/60 overflow-hidden flex flex-col z-10 animate-float">
                            <div class="h-11 border-b border-gray-100 bg-gray-50/70 flex items-center px-4 gap-2">
                                <div class="flex gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-gray-200"></div>
                                    <div class="w-3 h-3 rounded-full bg-gray-200"></div>
                                    <div class="w-3 h-3 rounded-full bg-gray-200"></div>
                                </div>
                                <div class="mx-auto bg-white border border-gray-200 rounded-md px-12 sm:px-16 py-1 flex items-center shadow-sm">
                                    <svg class="w-3 h-3 text-gray-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                    <span class="text-[10px] font-medium text-gray-400 truncate select-none">app.edufund.org/sarah-johnson</span>
                                </div>
                            </div>
                            
                            <div class="p-5 sm:p-6 bg-gray-50/20 flex flex-col gap-5">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                        <div class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                            <div class="w-1.5 h-1.5 rounded-full bg-[#8B5CF6]"></div> Total Funding
                                        </div>
                                        <div class="text-xl sm:text-2xl font-bold text-gray-900">$3,900 <span class="text-xs font-medium text-gray-400">USDC</span></div>
                                    </div>
                                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                        <div class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                            <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Verification Status
                                        </div>
                                        <div class="text-sm font-bold text-green-500 flex items-center gap-1 mt-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            3/6 Milestones Met
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex flex-col">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-[#FFF4ED] flex items-center justify-center text-[#E25B24] font-bold text-sm">SJ</div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">B.Sc. Computer Science</div>
                                                <div class="text-xs text-gray-400">Sarah Johnson &bull; Soroban Escrow</div>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-green-100 text-[10px] font-bold text-green-600 uppercase tracking-wider border border-green-200">
                                            Active Contract
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-1.5 mb-4">
                                        <div class="flex justify-between text-xs font-semibold text-gray-500">
                                            <span>Progress to Target</span>
                                            <span class="text-gray-900">78%</span>
                                        </div>
                                        <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-[#E25B24] rounded-full w-[78%]"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-2.5">
                                        <div class="h-[34px] flex-1 rounded-lg bg-gray-50 border border-gray-200 flex items-center justify-center text-xs font-medium text-gray-500 gap-1.5 select-none">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            View Ledger
                                        </div>
                                        <div class="h-[34px] flex-1 rounded-lg bg-[#E25B24]/10 border border-[#E25B24]/20 flex items-center justify-center text-xs font-bold text-[#E25B24] select-none">
                                            Disburse Funds
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="absolute -bottom-5 -left-5 bg-white px-4 py-3 rounded-xl border border-gray-200 shadow-xl flex items-center gap-3 z-20">
                            <div class="w-8 h-8 rounded-lg bg-[#8B5CF6]/10 flex items-center justify-center text-[#8B5CF6]">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" /></svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Institution Sign-Off</div>
                                <div class="text-xs font-bold text-gray-900">Milestone Verified</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 border-y border-gray-100 bg-white">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-8">Trusted by leading decentralized ecosystems</p>
                <div class="flex flex-wrap justify-center items-center gap-x-10 gap-y-6 lg:gap-x-16 opacity-40 grayscale hover:grayscale-0 transition-all duration-500">
                    <div class="flex items-center gap-2.5 text-gray-800 font-bold text-sm whitespace-nowrap"><svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5L12 22l10-8.5-5-2.5-5 2.5z"/></svg>EduTrust</div>
                    <div class="flex items-center gap-2.5 text-gray-800 font-bold text-sm whitespace-nowrap"><svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72l5 2.73 5-2.73v3.72z"/></svg>ScholarNet</div>
                    <div class="flex items-center gap-2.5 text-gray-800 font-bold text-sm whitespace-nowrap"><svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>BlockEd</div>
                    <div class="flex items-center gap-2.5 text-gray-800 font-bold text-sm whitespace-nowrap"><svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>LearnDAO</div>
                    <div class="flex items-center gap-2.5 text-gray-800 font-bold text-sm whitespace-nowrap"><svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 9h-2V7h-2v5H6v2h2v5h2v-5h2v-2z"/></svg>Soroban</div>
                </div>
            </div>
        </section>

        <section class="py-24 lg:py-32 bg-white">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-20">
                    <span class="inline-block px-3 py-1 bg-[#FFF4ED] rounded-full text-xs font-bold text-[#E25B24] mb-4">Why EduFund</span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight mb-5">
                        Scholarships should be <br class="sm:hidden"/> <span class="text-transparent bg-clip-text bg-gradient-to-br from-[#E25B24] to-[#F28C28]">transparent</span>, not a black box.
                    </h2>
                    <p class="text-base sm:text-lg text-gray-500 leading-relaxed font-normal">
                        Traditional education funding pathways are filled with bureaucratic overhead and zero end-to-end visibility. We replace opacity with cryptography.
                    </p>
                </div>
                
                <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-start">
                    <div class="space-y-6">
                        <div class="bg-white border border-gray-100 rounded-3xl shadow-sm transition-all duration-300 hover:border-gray-200 hover:shadow-md relative overflow-hidden p-8 flex flex-col">
                            <div class="w-11 h-11 rounded-xl bg-red-100 flex items-center justify-center mb-5 text-red-500">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">The Transparency Gap</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">Donors hand over liquidity without any ongoing insight into whether educational funding benchmarks are actually being honored or fulfilled.</p>
                        </div>
                        <div class="bg-white border border-gray-100 rounded-3xl shadow-sm transition-all duration-300 hover:border-gray-200 hover:shadow-md relative overflow-hidden p-8 flex flex-col">
                            <div class="w-11 h-11 rounded-xl bg-red-100 flex items-center justify-center mb-5 text-red-500">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Delayed Capital Deployments</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">Students encounter administrative roadblocks and extended waiting periods, preventing them from meeting real-time registration deadlines.</p>
                        </div>
                    </div>

                    <div class="space-y-6 lg:mt-8">
                        <div class="bg-white border border-[#E25B24]/20 rounded-3xl shadow-xl shadow-[#E25B24]/5 transition-all duration-300 hover:border-[#E25B24]/40 relative overflow-hidden p-8 flex flex-col">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#E25B24] to-[#F28C28] flex items-center justify-center mb-5 text-white shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">On-Chain Traceability</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">Every allocation, award, and disbursement milestone is explicitly structured and mapped via smart contracts directly to the open ledger.</p>
                        </div>
                        <div class="bg-white border border-[#E25B24]/20 rounded-3xl shadow-xl shadow-[#E25B24]/5 transition-all duration-300 hover:border-[#E25B24]/40 relative overflow-hidden p-8 flex flex-col">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#E25B24] to-[#F28C28] flex items-center justify-center mb-5 text-white shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Milestone Escrows</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">Donations sit safely inside audited escrows, unlocking only when partner educational institutions attest to validated student performance.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="how-it-works" class="py-24 lg:py-32 bg-white">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-20">
                    <span class="inline-block px-3 py-1 bg-[#8B5CF6]/10 rounded-full text-xs font-bold text-[#8B5CF6] mb-4 uppercase tracking-wider">Lifecycle Flow</span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight mb-5">Built on Trust. Verified on the Ledger.</h2>
                    <p class="text-base sm:text-lg text-gray-500 font-normal">A structured, continuous framework aligning students, donors, and academic verification authorities.</p>
                </div>
                
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md relative overflow-hidden p-7 flex flex-col transition-all duration-300 hover:-translate-y-1 group">
                        <div class="text-4xl font-extrabold text-gray-100 absolute top-6 right-6 transition-transform group-hover:-translate-y-1 select-none">01</div>
                        <div class="w-11 h-11 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center mb-6 text-gray-900">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900 mb-2">Campaign Setup</h3>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed mt-1">Students lay out structured semesters, funding requisites, and target criteria verified through secure Soroban environments.</p>
                    </div>

                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md relative overflow-hidden p-7 flex flex-col transition-all duration-300 hover:-translate-y-1 group">
                        <div class="text-4xl font-extrabold text-gray-100 absolute top-6 right-6 transition-transform group-hover:-translate-y-1 select-none">02</div>
                        <div class="w-11 h-11 rounded-xl bg-[#8B5CF6]/5 border border-[#8B5CF6]/20 flex items-center justify-center mb-6 text-[#8B5CF6]">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900 mb-2">Securing Escrow</h3>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed mt-1">Donors commit cryptographic capital (USDC/XLM) held directly inside programmatically isolated smart contracts.</p>
                    </div>

                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md relative overflow-hidden p-7 flex flex-col transition-all duration-300 hover:-translate-y-1 group">
                        <div class="text-4xl font-extrabold text-gray-100 absolute top-6 right-6 transition-transform group-hover:-translate-y-1 select-none">03</div>
                        <div class="w-11 h-11 rounded-xl bg-[#3B82F6]/5 border border-[#3B82F6]/20 flex items-center justify-center mb-6 text-[#3B82F6]">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900 mb-2">Institution Audit</h3>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed mt-1">Academic registrars authenticate criteria compliance, transcript data, and progress statements securely via verified dashboard portals.</p>
                    </div>

                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md relative overflow-hidden p-7 flex flex-col transition-all duration-300 hover:-translate-y-1 group">
                        <div class="text-4xl font-extrabold text-gray-100 absolute top-6 right-6 transition-transform group-hover:-translate-y-1 select-none">04</div>
                        <div class="w-11 h-11 rounded-xl bg-green-50 border border-green-200 flex items-center justify-center mb-6 text-green-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900 mb-2">Instant Release</h3>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed mt-1">Validated smart parameters trigger atomic execution, transferring the earmarked tuition tokens seamlessly.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="py-24 lg:py-32 bg-white">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-20">
                    <span class="inline-block px-3 py-1 bg-[#FFF4ED] rounded-full text-xs font-bold text-[#E25B24] mb-4">Features</span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight mb-5">Everything built for structural integrity</h2>
                    <p class="text-base sm:text-lg text-gray-500 font-normal">Premium developer design frameworks optimized for global philanthropic impact.</p>
                </div>
                
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-8 flex flex-col transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-gray-200">
                        <div class="w-11 h-11 rounded-xl bg-[#FFF4ED] flex items-center justify-center mb-6 text-[#E25B24]"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg></div>
                        <h3 class="text-base font-bold text-gray-900 mb-2">Funding Requests</h3>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">Students declare deterministic cost line-items, educational intent parameters, and explicit milestone deliverables.</p>
                    </div>
                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-8 flex flex-col transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-gray-200">
                        <div class="w-11 h-11 rounded-xl bg-[#FFF4ED] flex items-center justify-center mb-6 text-[#E25B24]"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg></div>
                        <h3 class="text-base font-bold text-gray-900 mb-2">Smart Event Alerts</h3>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">Real-time alerts broadcast state alterations instantly whenever milestones achieve institutional sign-offs.</p>
                    </div>
                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-8 flex flex-col transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-gray-200">
                        <div class="w-11 h-11 rounded-xl bg-[#FFF4ED] flex items-center justify-center mb-6 text-[#E25B24]"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg></div>
                        <h3 class="text-base font-bold text-gray-900 mb-2">Secure Escrows</h3>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">Soroban architectures securely wall off awarded allocations away from platform platform intervention layers.</p>
                    </div>
                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-8 flex flex-col transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-gray-200">
                        <div class="w-11 h-11 rounded-xl bg-[#FFF4ED] flex items-center justify-center mb-6 text-[#E25B24]"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg></div>
                        <h3 class="text-base font-bold text-gray-900 mb-2">Verified Identities</h3>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">Integrated structural KYC ensures all participating academic organizations maintain documented legitimacy.</p>
                    </div>
                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-8 flex flex-col transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-gray-200">
                        <div class="w-11 h-11 rounded-xl bg-[#FFF4ED] flex items-center justify-center mb-6 text-[#E25B24]"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg></div>
                        <h3 class="text-base font-bold text-gray-900 mb-2">Real-Time Analytics</h3>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">Inspect macro donation volumes, individual tracking milestones, and historical lifecycle metrics on demand.</p>
                    </div>
                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-8 flex flex-col transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-gray-200">
                        <div class="w-11 h-11 rounded-xl bg-[#FFF4ED] flex items-center justify-center mb-6 text-[#E25B24]"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                        <h3 class="text-base font-bold text-gray-900 mb-2">Borderless Rail Access</h3>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">Maximize capital efficiency leveraging Stellar's fast ledger transaction finality models.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="campaigns" class="py-24 lg:py-32 bg-white">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-16">
                    <div class="max-w-xl">
                        <span class="inline-block px-3 py-1 bg-[#FFF4ED] rounded-full text-xs font-bold text-[#E25B24] mb-4">Discover Funding</span>
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight mb-3">Verified Active Student Campaigns</h2>
                        <p class="text-base text-gray-500">Review historical achievement data points before allocating capital.</p>
                    </div>
                    <a href="{{ route('register') }}" class="shrink-0 inline-flex items-center gap-2 text-sm font-semibold text-gray-900 hover:text-[#E25B24] transition-colors border border-gray-200 rounded-xl px-4 py-2.5 hover:bg-gray-50">
                        Browse All Active Ledgers
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md hover:border-gray-200 relative overflow-hidden flex flex-col transition-all duration-300 hover:-translate-y-1">
                        <div class="p-6 sm:p-8 flex-1">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-11 h-11 rounded-full bg-[#FFF4ED] flex items-center justify-center text-sm font-bold text-[#E25B24]">SJ</div>
                                <div>
                                    <h3 class="font-bold text-sm text-gray-900">Sarah Johnson</h3>
                                    <p class="text-xs text-gray-400">B.Sc. Computer Science</p>
                                </div>
                                <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full bg-green-100 text-[10px] font-bold text-green-600 border border-green-200 uppercase tracking-wide">
                                    Active
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-500 mb-6 leading-relaxed line-clamp-3">
                                "Help me complete my final year in CS. I want to build accessible education technology for rural decentralized student networks."
                            </p>
                            <div class="mt-auto">
                                <div class="flex justify-between text-xs font-semibold text-gray-500 mb-2">
                                    <span>Raised pool</span>
                                    <span class="text-gray-900">$1,450 / $5,000</span>
                                </div>
                                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden mb-4">
                                    <div class="h-full bg-[#E25B24] w-[29%] rounded-full"></div>
                                </div>
                                <div class="flex justify-between text-[11px] text-gray-400 font-medium">
                                    <span>42 Ledger Depositors</span>
                                    <span>3/6 Milestones</span>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 sm:px-8 py-3.5 bg-gray-50/50 border-t border-gray-100 flex items-center gap-2 text-[11px] font-semibold text-gray-400 select-none">
                            <svg class="w-4 h-4 text-[#8B5CF6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            Verified by Stanford University
                        </div>
                    </div>

                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md hover:border-gray-200 relative overflow-hidden flex flex-col transition-all duration-300 hover:-translate-y-1">
                        <div class="p-6 sm:p-8 flex-1">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-11 h-11 rounded-full bg-[#3B82F6]/10 flex items-center justify-center text-sm font-bold text-[#3B82F6]">MC</div>
                                <div>
                                    <h3 class="font-bold text-sm text-gray-900">Marcus Chen</h3>
                                    <p class="text-xs text-gray-400">M.Sc. Data Science</p>
                                </div>
                                <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full bg-[#E25B24]/10 text-[10px] font-bold text-[#E25B24] border border-[#E25B24]/20 uppercase tracking-wide">
                                    Ending Soon
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-500 mb-6 leading-relaxed line-clamp-3">
                                "Funding my master's thesis on AI-driven climate modeling. Your cryptographic support directly accelerates infrastructure resource allocation."
                            </p>
                            <div class="mt-auto">
                                <div class="flex justify-between text-xs font-semibold text-gray-500 mb-2">
                                    <span>Raised pool</span>
                                    <span class="text-gray-900">$2,200 / $8,000</span>
                                </div>
                                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden mb-4">
                                    <div class="h-full bg-[#3B82F6] w-[27%] rounded-full"></div>
                                </div>
                                <div class="flex justify-between text-[11px] text-gray-400 font-medium">
                                    <span>18 Ledger Depositors</span>
                                    <span>1/4 Milestones</span>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 sm:px-8 py-3.5 bg-gray-50/50 border-t border-gray-100 flex items-center gap-2 text-[11px] font-semibold text-gray-400 select-none">
                            <svg class="w-4 h-4 text-[#8B5CF6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            Verified by MIT Infrastructure
                        </div>
                    </div>

                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md hover:border-gray-200 relative overflow-hidden p-6 sm:p-8 flex flex-col items-center justify-center text-center transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-white border border-gray-200 shadow-sm flex items-center justify-center mb-5 text-gray-900">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        </div>
                        <h3 class="font-bold text-base text-gray-900 mb-1.5">Applying for Funding?</h3>
                        <p class="text-xs sm:text-sm text-gray-400 mb-6 max-w-[220px] leading-relaxed">Map your academic parameters and request on-chain assistance today.</p>
                        <a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center px-5 py-2.5 text-xs font-bold text-gray-900 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-colors">
                            Initialize Application
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 lg:py-36 bg-white">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#E25B24] to-[#F28C28] p-8 sm:p-12 lg:p-16 text-center shadow-xl shadow-[#E25B24]/10">
                    <div class="relative z-10 max-w-xl mx-auto">
                        <h2 class="text-3xl sm:text-4xl font-bold text-white tracking-tight mb-4">Ready to fund the future of education?</h2>
                        <p class="text-sm sm:text-base text-white/80 mb-8 max-w-md mx-auto leading-relaxed">Join thousands of students, schools, and global donors deploying trust capital on the open blockchain architecture.</p>
                        <div class="flex flex-col sm:flex-row items-center gap-4 justify-center">
                            <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 text-sm font-bold text-[#E25B24] bg-white rounded-xl hover:shadow-xl transition-all duration-300 transform hover:scale-[1.01]">
                                Get Started Now
                                <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                            <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 text-sm font-bold text-white border border-white/30 rounded-xl hover:bg-white/10 transition-all duration-300">
                                Sign In
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-gray-100 bg-white pt-16 pb-12">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 lg:gap-12 mb-16">
                <div class="col-span-2 lg:col-span-2">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 mb-5">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#E25B24] to-[#F28C28] flex items-center justify-center text-white font-bold text-base">E</div>
                        <span class="text-lg font-bold text-gray-900">Edu<span class="text-[#E25B24]">Fund</span></span>
                    </a>
                    <p class="text-xs sm:text-sm text-gray-400 leading-relaxed max-w-sm">
                        Decentralizing student scholarship pipelines through autonomous programmatic ledger configurations on Stellar Soroban contracts.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4">Platform</h4>
                    <ul class="space-y-3">
                        <li><a href="#how-it-works" class="text-xs sm:text-sm text-gray-500 hover:text-gray-900 transition-colors">How It Works</a></li>
                        <li><a href="#campaigns" class="text-xs sm:text-sm text-gray-500 hover:text-gray-900 transition-colors">Active Campaigns</a></li>
                        <li><a href="{{ route('register') }}" class="text-xs sm:text-sm text-gray-500 hover:text-gray-900 transition-colors">Student Apply</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4">Resources</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-xs sm:text-sm text-gray-500 hover:text-gray-900 transition-colors">Documentation</a></li>
                        <li><a href="#" class="text-xs sm:text-sm text-gray-500 hover:text-gray-900 transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-xs sm:text-sm text-gray-500 hover:text-gray-900 transition-colors">Smart Contracts</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4">Legal</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-xs sm:text-sm text-gray-500 hover:text-gray-900 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-xs sm:text-sm text-gray-500 hover:text-gray-900 transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-gray-400">&copy; {{ date('Y') }} EduFund Inc. All rights reserved.</p>
                <div class="flex items-center gap-2 text-xs text-gray-400 font-medium select-none">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    Mainnet Status Operational &bull; Built on Stellar
                </div>
            </div>
        </div>
    </footer>
</div>

<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({ pageLanguage: 'en', autoDisplay: false }, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>