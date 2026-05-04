<div class="space-y-6">

    {{-- Upcoming Matches Section --}}
    <div class="mb-12 space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-display font-black uppercase tracking-tight">Upcoming <span class="text-primary">Matches</span></h3>
            <span class="px-3 py-1 rounded-full bg-white/5 text-[10px] font-black uppercase tracking-widest opacity-40">{{ $upcomingMatches->count() }} Scheduled</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($upcomingMatches as $match)
                <div class="glass p-6 rounded-3xl border border-white/5 flex items-center justify-between group hover:border-primary/30 transition-all duration-500">
                    <div class="flex items-center gap-4 w-2/5">
                        <div class="w-12 h-12 rounded-2xl bg-dark-bg border border-white/5 flex items-center justify-center overflow-hidden">
                            <img src="https://api.dicebear.com/7.x/identicon/svg?seed={{ urlencode($match->teamA->name) }}" class="w-8 h-8">
                        </div>
                        <span class="font-black text-sm uppercase truncate">{{ $match->teamA->name }}</span>
                    </div>

                    <div class="flex flex-col items-center flex-1">
                        @if($match->scheduled_at)
                            <div class="bg-primary/10 text-primary px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest border border-primary/20">
                                {{ $match->scheduled_at->format('H:i') }}
                            </div>
                            <span class="text-[10px] font-bold opacity-30 mt-2 uppercase tracking-tighter">{{ $match->scheduled_at->format('d M, Y') }}</span>
                        @else
                            <div class="bg-white/5 text-white/40 px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest border border-white/10 border-dashed">
                                TBD
                            </div>
                            <span class="text-[10px] font-bold opacity-20 mt-2 uppercase">Waiting for schedule</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-4 w-2/5 text-right">
                        <span class="font-black text-sm uppercase truncate">{{ $match->teamB->name }}</span>
                        <div class="w-12 h-12 rounded-2xl bg-dark-bg border border-white/5 flex items-center justify-center overflow-hidden">
                            <img src="https://api.dicebear.com/7.x/identicon/svg?seed={{ urlencode($match->teamB->name) }}" class="w-8 h-8">
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full glass p-10 rounded-3xl border border-white/5 text-center opacity-40">
                    <p class="text-xs font-black uppercase tracking-widest">No matches scheduled yet</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Admin: modal button --}}
    @if(auth()->user()?->hasRole('admin') || auth()->user()?->hasRole('super_admin'))
        {{-- managed from /admin/announcements --}}

    {{-- Captain: postule button --}}
    @elseif(auth()->user()?->hasRole('captain'))
        @php $userTeam = $userTeam ?? null; @endphp
        @if($userTeam && $tournament->status === 'Upcoming')
            @if(!$registrationStatus)
                <div class="flex justify-end">
                    <form method="POST" action="{{ route('tournaments.register', $tournament->slug) }}">
                        @csrf
                        <input type="hidden" name="team_id" value="{{ $userTeam->id }}">
                        <x-ui.button type="submit" variant="primary" size="sm">
                            Apply to Tournament
                        </x-ui.button>
                    </form>
                </div>
            @endif
        @endif
    @endif

    {{-- Announcements List --}}
    @forelse($tournament->announcements as $ann)
        <div class="glass rounded-3xl border border-white/5 overflow-hidden hover:border-primary/10 transition-all">
            <div class="flex items-center justify-between px-6 py-4 border-b border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-sm">📢</div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest">{{ $ann->author?->firstname }} {{ $ann->author?->lastname }}</p>
                        <p class="text-[10px] text-gray-500">{{ $ann->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @if(auth()->user()?->hasRole('admin') || auth()->user()?->hasRole('super_admin'))
                    <form method="POST" action="{{ route('admin.tournaments.announcements.destroy', $ann) }}">
                        @csrf @method('DELETE')
                        <button class="text-gray-500 hover:text-danger transition-colors text-xs font-bold">✕ Delete</button>
                    </form>
                @endif
            </div>
            <div class="p-6 space-y-2">
                <h4 class="font-black uppercase tracking-tight">{{ $ann->title }}</h4>
                <p class="text-sm text-gray-300 leading-relaxed">{{ $ann->body }}</p>
            </div>
        </div>
    @empty
        <div class="glass p-12 rounded-3xl border border-white/5 text-center opacity-40">
            <p class="text-sm font-bold uppercase tracking-widest">No announcements yet</p>
        </div>
    @endforelse
</div>
