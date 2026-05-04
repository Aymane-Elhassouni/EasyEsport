<div class="overflow-x-auto pb-20 custom-scrollbar">
    <div class="min-w-[1280px] flex items-start justify-between gap-16 pt-12 px-4 relative">
        
        @php
            $rounds = $tournament->brackets->sortBy('round');
            $round1 = $rounds->where('round', 1)->first();
            $round2 = $rounds->where('round', 2)->first();
            $round3 = $rounds->where('round', 3)->first();
        @endphp

        {{-- Round 1 / Quarter Finals (or similar) --}}
        <div class="space-y-12 flex-1">
             <div class="text-center mb-10">
                <p class="text-[10px] font-black opacity-30 uppercase tracking-[0.4em]">
                    {{ $tournament->format === 'single_elimination' ? 'Quarter-Finals' : 'Round 1' }}
                </p>
             </div>
             @if($round1 && $round1->matches->count())
                 @foreach($round1->matches as $match)
                    <div class="w-full glass rounded-3xl border border-white/5 p-6 relative group hover:border-primary/40 transition-all duration-500 hover:shadow-2xl">
                         <div class="space-y-4">
                            <div class="flex items-center justify-between {{ $match->winner_id && $match->winner_id == $match->team_a_id ? 'text-primary' : 'opacity-80' }} group-hover:opacity-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded bg-dark-bg border border-white/10 shadow-inner overflow-hidden">
                                        @if($match->teamA)
                                            <img src="https://api.dicebear.com/7.x/identicon/svg?seed={{ urlencode($match->teamA->name) }}" class="w-full h-full">
                                        @endif
                                    </div>
                                    <span class="text-xs font-black tracking-tight uppercase truncate max-w-[120px]">
                                        {{ $match->teamA->name ?? 'TBD' }}
                                    </span>
                                </div>
                                <span class="text-xs font-black {{ $match->winner_id == $match->team_a_id ? 'text-primary' : 'opacity-30' }}">
                                    {{ $match->score_a ?? '--' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between border-t border-white/5 pt-4 {{ $match->winner_id && $match->winner_id == $match->team_b_id ? 'text-primary' : 'opacity-40' }} group-hover:opacity-60">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded bg-dark-bg border border-white/10 shadow-inner overflow-hidden">
                                        @if($match->teamB)
                                            <img src="https://api.dicebear.com/7.x/identicon/svg?seed={{ urlencode($match->teamB->name) }}" class="w-full h-full">
                                        @endif
                                    </div>
                                    <span class="text-xs font-black tracking-tight uppercase truncate max-w-[120px]">
                                        {{ $match->teamB->name ?? ($match->status === 'completed' && !$match->team_b_id ? 'BYE' : 'TBD') }}
                                    </span>
                                </div>
                                <span class="text-xs font-black {{ $match->winner_id == $match->team_b_id ? 'text-primary' : 'opacity-30' }}">
                                    {{ $match->score_b ?? '--' }}
                                </span>
                            </div>
                         </div>
                         @if($rounds->count() > 1)
                            <div class="absolute top-1/2 -right-16 w-16 h-[1px] bg-white/10 group-hover:bg-primary/30 transition-colors"></div>
                         @endif
                    </div>
                 @endforeach
             @else
                 @foreach(range(1, 4) as $i)
                    <div class="w-full glass rounded-3xl border border-white/5 p-6 opacity-20">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3"><div class="w-7 h-7 rounded bg-white/5"></div><span class="text-xs font-black">TBD</span></div>
                            <div class="flex items-center gap-3 border-t border-white/5 pt-4"><div class="w-7 h-7 rounded bg-white/5"></div><span class="text-xs font-black">TBD</span></div>
                        </div>
                    </div>
                 @endforeach
             @endif
        </div>

        {{-- Round 2 / Semi Finals --}}
        <div class="space-y-32 flex-1">
            <div class="text-center mb-10">
                <p class="text-[10px] font-black opacity-30 uppercase tracking-[0.4em]">Semi-Finals</p>
             </div>
             @if($round2 && $round2->matches->count())
                 @foreach($round2->matches as $match)
                    <div class="w-full glass rounded-3xl border border-primary/20 p-6 relative group hover:shadow-neon-primary transition-all duration-500">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between {{ $match->winner_id == $match->team_a_id ? 'text-primary' : 'text-gray-400' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded bg-primary/10 border border-primary/20 overflow-hidden">
                                        @if($match->teamA)
                                            <img src="https://api.dicebear.com/7.x/identicon/svg?seed={{ urlencode($match->teamA->name) }}" class="w-full h-full">
                                        @endif
                                    </div>
                                    <span class="text-xs font-black tracking-tight uppercase truncate max-w-[120px]">
                                        {{ $match->teamA->name ?? 'ROUND 1 WINNER' }}
                                    </span>
                                </div>
                                <span class="text-xs font-black">{{ $match->score_a ?? '--' }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-white/5 pt-4 {{ $match->winner_id == $match->team_b_id ? 'text-primary' : 'opacity-30' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded bg-dark-bg border border-white/10 overflow-hidden">
                                        @if($match->teamB)
                                            <img src="https://api.dicebear.com/7.x/identicon/svg?seed={{ urlencode($match->teamB->name) }}" class="w-full h-full">
                                        @endif
                                    </div>
                                    <span class="text-xs font-black tracking-tight uppercase truncate max-w-[120px]">
                                        {{ $match->teamB->name ?? 'ROUND 1 WINNER' }}
                                    </span>
                                </div>
                                <span class="text-xs font-black">{{ $match->score_b ?? '--' }}</span>
                            </div>
                         </div>
                         <div class="absolute top-1/2 -left-16 w-16 h-[1px] bg-primary/20"></div>
                         @if($rounds->count() > 2)
                            <div class="absolute top-1/2 -right-16 w-16 h-[1px] bg-white/10 group-hover:bg-secondary/30 transition-colors"></div>
                         @endif
                    </div>
                 @endforeach
             @else
                @foreach(range(1, 2) as $i)
                    <div class="w-full glass rounded-3xl border border-white/5 p-6 opacity-20">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3"><div class="w-7 h-7 rounded bg-white/5"></div><span class="text-xs font-black">TBD</span></div>
                            <div class="flex items-center gap-3 border-t border-white/5 pt-4"><div class="w-7 h-7 rounded bg-white/5"></div><span class="text-xs font-black">TBD</span></div>
                        </div>
                    </div>
                @endforeach
             @endif
        </div>

        {{-- Round 3 / Grand Finals --}}
        <div class="flex-1 flex flex-col items-center justify-center">
            <div class="text-center mb-10">
                <p class="text-[10px] font-black text-warning uppercase tracking-[0.4em] animate-pulse">The Grand Final</p>
             </div>
            <div class="w-full max-w-[400px] glass rounded-[4rem] border-2 border-warning/30 p-12 relative group shadow-neon-primary animate-float overflow-hidden">
                <div class="absolute inset-0 bg-warning/5 opacity-20 group-hover:opacity-40 transition-opacity"></div>
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-warning to-transparent"></div>
                
                <div class="space-y-16 relative z-10 text-center">
                    <div class="space-y-6">
                        <div class="w-24 h-24 bg-warning/10 rounded-[2rem] flex items-center justify-center mx-auto shadow-neon-primary border border-warning/30 group-hover:scale-110 transition-transform duration-700">
                             <svg class="w-12 h-12 text-warning" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1a1 1 0 112 0v1a1 1 0 11-2 0zM13 16v-1a1 1 0 112 0v1a1 1 0 11-2 0zM14.95 14.95a1 1 0 101.414-1.414l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM6.464 14.95a1 1 0 10-1.414-1.414l.707.707a1 1 0 001.414-1.414l-.707-.707z" /></svg>
                        </div>
                        <h4 class="text-2xl font-display font-black tracking-tighter uppercase line-clamp-1">{{ $tournament->name }}</h4>
                    </div>

                    <div class="space-y-6">
                        @php
                            $finalMatch = $round3 ? $round3->matches->first() : null;
                        @endphp
                         <div class="glass p-6 rounded-3xl border border-white/5 {{ $finalMatch && $finalMatch->teamA ? '' : 'opacity-30 grayscale' }} group-hover:grayscale-0 transition-all">
                            <p class="text-[10px] font-black uppercase opacity-40 mb-2 tracking-widest">Finalist A</p>
                            <p class="text-xl font-black tracking-tighter uppercase truncate">{{ $finalMatch->teamA->name ?? 'TBD' }}</p>
                         </div>
                         <div class="flex items-center justify-center gap-4">
                            <div class="h-[1px] flex-1 bg-white/5"></div>
                            <div class="text-xs font-black text-warning">VS</div>
                            <div class="h-[1px] flex-1 bg-white/5"></div>
                         </div>
                         <div class="glass p-6 rounded-3xl border border-white/5 {{ $finalMatch && $finalMatch->teamB ? '' : 'opacity-30 grayscale' }} group-hover:grayscale-0 transition-all">
                            <p class="text-[10px] font-black uppercase opacity-40 mb-2 tracking-widest">Finalist B</p>
                            <p class="text-xl font-black tracking-tighter uppercase truncate">{{ $finalMatch->teamB->name ?? 'TBD' }}</p>
                         </div>
                    </div>
                </div>
                
                <!-- Connector -->
                <div class="absolute top-1/2 -left-16 w-16 h-[1px] bg-white/10"></div>
            </div>
        </div>

    </div>
</div>
