<x-layouts.guest title="EasyEsport — Compete. Win. Dominate.">

    {{-- Hero --}}
    <section class="relative min-h-screen flex items-center pt-24 overflow-hidden">

        {{-- Ambient blobs --}}
        <div class="absolute top-0 right-0 w-[700px] h-[700px] bg-primary/20 blur-[150px] rounded-full translate-x-1/3 -translate-y-1/3 animate-pulse-soft pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-secondary/10 blur-[120px] rounded-full -translate-x-1/3 translate-y-1/3 pointer-events-none"></div>
        <div class="absolute inset-0 bg-grid-white opacity-[0.03] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 w-full grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- Left: Copy --}}
            <div class="space-y-8 text-center lg:text-left animate-fade-in">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/10 border border-primary/20 text-xs font-bold text-primary tracking-[0.2em] uppercase">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    Next-Gen eSport Platform
                </div>

                <h1 class="text-5xl md:text-7xl font-display font-black tracking-tight leading-[0.9] uppercase">
                    Compete.<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-secondary to-primary">
                        Win.
                    </span><br>
                    Dominate.
                </h1>

                <p class="text-gray-400 text-lg max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Join the most competitive eSport platform. Register for tournaments, track your stats, and climb the leaderboard.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}"
                       class="px-10 py-4 bg-primary text-white text-sm font-bold rounded-2xl uppercase tracking-widest shadow-neon-primary hover:bg-primary-light hover:scale-105 transition-all text-center">
                        Get Started Free
                    </a>
                    <a href="{{ route('login') }}"
                       class="px-10 py-4 bg-white/5 border border-white/10 text-white text-sm font-bold rounded-2xl uppercase tracking-widest hover:bg-white/10 transition-all text-center">
                        Sign In
                    </a>
                </div>

                {{-- Stats --}}
                <div class="flex flex-wrap justify-center lg:justify-start gap-8 pt-4">
                    @foreach([['50k+', 'Matches'], ['1.2k', 'Teams'], ['$500k+', 'Prize Pool']] as $s)
                        <div class="text-center lg:text-left">
                            <p class="text-2xl font-display font-black text-primary">{{ $s[0] }}</p>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500">{{ $s[1] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right: Visual --}}
            <div class="hidden lg:block relative">
                <div class="relative glass rounded-[3rem] p-8 border border-white/10 shadow-2xl rotate-2 hover:rotate-0 transition-transform duration-700 group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-primary to-secondary rounded-[3.1rem] blur opacity-20 group-hover:opacity-40 transition-opacity"></div>
                    <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=800"
                         class="w-full rounded-[2rem] shadow-2xl relative z-10" alt="Gaming">

                    <div class="absolute -top-10 -left-10 glass p-5 rounded-2xl border border-white/10 shadow-neon-primary animate-float z-20">
                        <p class="text-[10px] font-black opacity-50 uppercase mb-1">Prize Pool</p>
                        <p class="text-2xl font-black text-primary">$500K+</p>
                    </div>
                    <div class="absolute -bottom-8 -right-8 glass p-5 rounded-2xl border border-white/10 shadow-neon-secondary animate-float z-20" style="animation-delay:1.5s">
                        <p class="text-[10px] font-black opacity-50 uppercase mb-1">Active Players</p>
                        <p class="text-2xl font-black text-secondary">24.5K</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="py-28">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16 space-y-3">
                <p class="text-[10px] font-black uppercase tracking-[0.4em] text-primary">Platform Features</p>
                <h2 class="text-4xl md:text-5xl font-display font-black uppercase tracking-tight">
                    Everything You Need To <span class="text-primary">Dominate</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach([
                    ['title' => 'AI OCR Validation',  'desc' => 'Instant match result verification using advanced screenshot pattern recognition. No more manual disputes.'],
                    ['title' => 'Elite Tournaments',   'desc' => 'Professional leagues with real-time brackets, automatic scheduling, and secured prize pools.'],
                    ['title' => 'Team Management',     'desc' => 'Build your organization, recruit top talent, and manage your roster with deep analytics.'],
                ] as $f)
                    <div class="glass p-10 rounded-[3rem] border border-white/5 hover:border-primary/30 transition-all duration-500 group">
                        <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary transition-all duration-300">
                            <svg class="w-7 h-7 text-primary group-hover:text-white transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-display font-black mb-3 group-hover:text-primary transition-colors uppercase tracking-tight">{{ $f['title'] }}</h3>
                        <p class="text-gray-500 leading-relaxed text-sm">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-28">
        <div class="max-w-4xl mx-auto px-6 text-center glass rounded-[4rem] p-16 border border-white/5 relative overflow-hidden group">
            <div class="absolute inset-0 bg-primary/10 blur-[100px] opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
            <div class="relative z-10 space-y-8">
                <h2 class="text-5xl font-display font-black uppercase tracking-tight">
                    Ready For The <span class="text-primary">Big League?</span>
                </h2>
                <p class="text-gray-400 max-w-md mx-auto">Create your account in seconds and join the most competitive community.</p>
                <a href="{{ route('register') }}"
                   class="inline-block px-14 py-5 bg-primary text-white text-sm font-bold rounded-2xl uppercase tracking-widest shadow-neon-primary hover:scale-105 transition-all">
                    Sign Up Free
                </a>
            </div>
        </div>
    </section>

</x-layouts.guest>
