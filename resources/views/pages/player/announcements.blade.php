<x-layouts.app title="Announcements">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-display font-black uppercase tracking-tight">
                <span class="text-primary">Announcements</span>
            </h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">
                {{ $announcements->count() }} announcements
            </p>
        </div>
    </div>

    @if(!$team)
        <div class="glass p-16 rounded-3xl border border-white/5 text-center opacity-40">
            <p class="text-sm font-bold uppercase tracking-widest">You need a team to see announcements</p>
        </div>
    @else
        <div class="space-y-6">
            @forelse($announcements as $ann)
                <div class="glass rounded-3xl border border-white/5 overflow-hidden hover:border-primary/20 transition-all group">

                    {{-- Banner --}}
                    @if($ann->bannerUrl)
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ $ann->bannerUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-4 left-6 flex items-center gap-2">
                                <span class="px-3 py-1 rounded-lg bg-primary/20 border border-primary/30 text-primary text-[10px] font-black uppercase tracking-widest backdrop-blur-sm">
                                    {{ $ann->tournamentName }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="p-6">
                        {{-- Meta --}}
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-sm">📢</div>
                                <div>
                                    @if(!$ann->bannerUrl)
                                        <p class="text-xs font-black uppercase tracking-widest text-primary">{{ $ann->tournamentName }}</p>
                                    @endif
                                    <p class="text-[10px] text-gray-500">{{ $ann->timeAgo }} · {{ $ann->authorName }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($ann->gameLogo)
                                    <img src="{{ $ann->gameLogo }}" class="w-6 h-6 rounded-lg opacity-50">
                                @endif
                                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">{{ $ann->gameName }}</span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <h3 class="font-display font-black text-lg uppercase tracking-tight mb-2">{{ $ann->title }}</h3>
                        <p class="text-sm text-gray-400 leading-relaxed">{{ $ann->body }}</p>

                        {{-- Footer --}}
                        <div class="mt-5 pt-4 border-t border-white/5 flex items-center justify-between">
                            <a href="{{ route('tournaments.show', $ann->tournamentSlug) }}"
                               class="inline-flex items-center gap-2 text-xs font-bold text-primary hover:text-primary/80 transition-colors">
                                View Tournament
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="/player/announcements/{{ $ann->slug }}"
                               class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-white transition-colors">
                                Read more
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass p-16 rounded-3xl border border-white/5 text-center opacity-40">
                    <p class="text-sm font-bold uppercase tracking-widest">No announcements yet</p>
                    <p class="text-xs mt-2 opacity-60">Check back later for updates from your tournaments</p>
                </div>
            @endforelse
        </div>
    @endif

</x-layouts.app>
