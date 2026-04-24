<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="themeManager" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'EasyEsport' }} - Premium Gaming Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark-bg text-white font-sans selection:bg-primary">

    <!-- Guest Navbar -->
    <nav class="fixed top-0 inset-x-0 z-50 transition-all duration-300" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div :class="scrolled ? 'glass py-3 px-8 rounded-2xl border-white/10 shadow-2xl' : 'py-3'" class="flex items-center justify-between transition-all duration-500">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center shadow-neon-primary">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-xl font-display font-black tracking-tighter">EASY<span class="text-primary font-medium tracking-normal">ESPORT</span></span>
                </div>

                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-xs font-bold uppercase tracking-widest opacity-60 hover:opacity-100 hover:text-primary transition-all">Features</a>
                    <a href="{{ route('tournaments') }}" class="text-xs font-bold uppercase tracking-widest opacity-60 hover:opacity-100 hover:text-primary transition-all">Tournaments</a>
                    <a href="{{ route('teams') }}" class="text-xs font-bold uppercase tracking-widest opacity-60 hover:opacity-100 hover:text-primary transition-all">Teams</a>
                    <a href="{{ route('about') }}" class="text-xs font-bold uppercase tracking-widest opacity-60 hover:opacity-100 hover:text-primary transition-all">About</a>
                </div>

                <div class="flex items-center gap-4">
                    @if(isset($authUser) && $authUser)
                        @php
                            $dashboardRoute = $authUser->hasRole('admin') || $authUser->hasRole('super_admin')
                                ? route('admin.dashboard')
                                : route('player.dashboard');
                        @endphp
                        <a href="{{ $dashboardRoute }}" class="btn-primary shadow-neon-primary px-8 py-2.5 text-xs font-bold tracking-widest rounded-xl uppercase">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-widest opacity-60 hover:opacity-100 px-4 py-2">Sign In</a>
                        <a href="{{ route('register') }}" class="btn-primary shadow-neon-primary px-6 py-2.5 text-xs font-bold tracking-widest rounded-xl">JOIN NOW</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-[#0b1120] border-t border-white/5 py-20 mt-20">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-2 space-y-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-lg font-display font-black tracking-tighter uppercase">EasyEsport</span>
                </div>
                <p class="text-gray-500 max-w-sm leading-relaxed">The world's most advanced platform for competitive gaming automation and player management.</p>
                <div class="flex gap-4">
                    @foreach(['twitter', 'discord', 'twitch', 'github'] as $social)
                        <a href="#" class="w-10 h-10 rounded-xl glass border-white/5 flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                             <img src="https://api.dicebear.com/7.x/identicon/svg?seed={{ $social }}" class="w-5 h-5 opacity-50 filter grayscale-0">
                        </a>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 class="font-bold mb-6 uppercase tracking-widest text-xs opacity-40">Platform</h4>
                <ul class="space-y-4 text-sm font-medium text-gray-400">
                    <li><a href="#" class="hover:text-primary transition-colors">How it works</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">OCR Technology</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Pro Leagues</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Terms of Use</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-6 uppercase tracking-widest text-xs opacity-40">Support</h4>
                <ul class="space-y-4 text-sm font-medium text-gray-400">
                    <li><a href="#" class="hover:text-primary transition-colors">Help Center</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Contact Us</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">FAQ</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">API Docs</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 mt-20 pt-8 border-t border-white/5 flex justify-between items-center text-[10px] font-bold text-gray-600 uppercase tracking-widest">
            <p>© 2024 EasyEsport. Built for the elite.</p>
            <p>Designed by Antigravity AI</p>
        </div>
    </footer>

</body>
</html>
