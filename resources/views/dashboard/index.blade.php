<x-layouts.app :title="'Overview'">
    
    <!-- Top Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-card title="Global Rank" icon='<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16M10 14.66V17M14 14.66V17M18 2H6v7a6 6 0 0 0 12 0V2z" />'>
            <div class="mt-2">
                <span class="text-4xl font-display font-black text-primary">{{ $rankName ?? 'Unranked' }}</span>
                <div class="mt-2 flex items-center gap-2 text-xs font-bold text-success">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>+12 positions today</span>
                </div>
            </div>
        </x-card>

        <x-card title="Win Rate" icon='<path d="M12 20V10M18 20V4M6 20v-6" />'>
            <div class="mt-2">
                <span class="text-4xl font-display font-black">{{ $winRate ?? 0 }}%</span>
                <div class="mt-4 w-full h-1.5 bg-dark-bg/20 dark:bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-primary rounded-full shadow-neon-primary transition-all duration-1000" style="width: {{ $winRate ?? 0 }}%"></div>
                </div>
            </div>
        </x-card>

        <x-card title="Matches Played" icon='<path d="M13 10V3L4 14h7v7l9-11h-7z" />'>
            <div class="mt-2">
                <span class="text-4xl font-display font-black">{{ Auth::user()->profile->total_matches ?? 0 }}</span>
                <p class="text-[10px] mt-1 opacity-50 uppercase tracking-widest">Lifetime stats</p>
            </div>
        </x-card>

        <x-card title="Active Teams" icon='<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 00-3-3.87" /><path d="M16 3.13a4 4 0 010 7.75" />'>
            <div class="mt-2 flex -space-x-3 overflow-hidden mt-4">
                <template x-for="i in 4" :key="i">
                    <!-- Fake Team Avatars -->
                    <img class="inline-block h-10 w-10 rounded-xl ring-4 ring-light-bg dark:ring-dark-bg hover:translate-y-[-4px] transition-transform cursor-pointer bg-dark-surface" 
                         :src="`https://api.dicebear.com/7.x/identicon/svg?seed=team${i}`" alt="team">
                </template>
                <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-primary/20 text-primary text-xs font-bold ring-4 ring-light-bg dark:ring-dark-bg z-10">+2</div>
            </div>
        </x-card>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Col: Recent Matches -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-display font-bold">Recent Matches</h2>
                    <p class="text-xs opacity-50 uppercase tracking-widest">Your latest match history</p>
                </div>
                <button class="text-xs font-bold text-primary hover:underline">View Full Log</button>
            </div>

            <div class="space-y-4">
                <template x-for="match in $store.global.matches" :key="match.id">
                    <div class="glass border border-white/5 rounded-2xl p-4 flex items-center justify-between group hover:bg-white/5 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-dark-bg flex items-center justify-center border border-white/5 overflow-hidden">
                                <img :src="`https://api.dicebear.com/7.x/initials/svg?seed=${match.opponent}`" class="w-8 h-8 opacity-80">
                            </div>
                            <div>
                                <p class="text-sm font-bold" x-text="match.opponent"></p>
                                <p class="text-[10px] opacity-50 uppercase font-bold" x-text="match.time"></p>
                            </div>
                        </div>
                        
                        <div class="text-center px-4 md:px-6">
                            <p class="text-xl font-display font-black tracking-widest" x-text="match.score"></p>
                        </div>

                        <div class="flex items-center gap-4">
                            <template x-if="match.status === 'win'">
                                <span class="px-3 py-1 bg-success/10 text-success text-[10px] font-bold rounded-lg border border-success/20 uppercase tracking-widest shadow-neon-success hidden sm:block">Victory</span>
                            </template>
                            <template x-if="match.status === 'loss'">
                                <span class="px-3 py-1 bg-danger/10 text-danger text-[10px] font-bold rounded-lg border border-danger/20 uppercase tracking-widest hidden sm:block">Defeat</span>
                            </template>
                            
                            <button class="p-2 md:opacity-0 group-hover:opacity-100 transition-opacity hover:bg-primary/10 text-primary rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Right Col: OCR Card / Tournaments -->
        <div class="space-y-6">
            <h2 class="text-xl font-display font-bold">Quick Actions</h2>
            
            <a href="{{ route('upload') }}" class="block glass border-2 border-primary/20 rounded-3xl p-6 text-center hover:border-primary/50 transition-all group overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10 flex flex-col items-center gap-4">
                    <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                    </div>
                    <div>
                        <p class="font-bold text-lg">Scan Match Result</p>
                        <p class="text-xs opacity-50 font-medium">Use OCR to validate your score</p>
                    </div>
                    <span class="btn-primary py-2 px-6 w-full shadow-neon-primary text-sm mt-2 font-semibold">Upload Image</span>
                </div>
            </a>
            
            <div class="glass border border-white/5 rounded-3xl p-6">
                <h3 class="text-sm font-bold mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-warning rounded-full animate-pulse-soft"></span>
                    Trending Tournaments
                </h3>
                <div class="space-y-3">
                    <template x-for="t in $store.global.tournaments.slice(0, 2)" :key="t.id">
                        <div class="p-3 bg-dark-bg/20 dark:bg-white/5 border border-white/5 rounded-xl hover:bg-white/10 transition-colors cursor-pointer">
                            <p class="font-bold text-sm truncate" x-text="t.name"></p>
                            <div class="flex justify-between items-center mt-2 text-xs">
                                <span class="opacity-50 uppercase font-bold" x-text="t.game"></span>
                                <span class="text-warning font-black" x-text="t.prize"></span>
                            </div>
                        </div>
                    </template>
                </div>
                <a href="{{ route('tournaments') }}" class="block mt-4 text-center text-xs font-bold text-primary hover:underline">View All Tournaments</a>
            </div>

        </div>
    </div>
</x-layouts.app>
