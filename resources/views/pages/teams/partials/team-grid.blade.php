<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($teams as $team)
        <div class="glass p-6 rounded-3xl border border-white/5 hover:border-primary/30 transition-all group animate-slide-up">
            <div class="flex items-center gap-4 mb-4">
                <img src="{{ $team->logo_url }}" class="w-14 h-14 rounded-2xl object-cover border border-white/10">
                <div>
                    <h3 class="font-black uppercase tracking-tight">{{ $team->name }}</h3>
                    <p class="text-xs opacity-50 mt-0.5">{{ $team->getFormattedMembersCount() }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-white/5">
                <span class="text-[10px] font-bold uppercase tracking-widest {{ $team->getStatusBadgeClass() }}">
                    {{ $team->status }}
                </span>
                @auth
                    @if(auth()->user()->hasRole('player') && $team->canJoin())
                        <div x-data="{ done: false }">
                            <button x-show="!done"
                                @click="
                                    fetch('{{ route('teams.join-request', $team->slug) }}', {
                                        method: 'POST',
                                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
                                    }).then(r => { if(r.ok) done = true });
                                "
                                class="px-4 py-2 bg-primary/10 text-primary border border-primary/20 rounded-xl text-xs font-bold hover:bg-primary hover:text-white transition-all">
                                REQUEST TO JOIN
                            </button>
                            <span x-show="done" x-cloak
                                class="px-4 py-2 bg-warning/10 text-warning border border-warning/20 rounded-xl text-xs font-bold uppercase tracking-widest">Pending</span>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    @empty
        <div class="col-span-full glass p-12 rounded-3xl border border-white/5 text-center opacity-40">
            <p class="text-sm font-medium uppercase tracking-widest">No teams found matching your search</p>
            @if(request('q'))
                <button @click="search = ''; fetchTeams()" class="text-xs text-primary mt-4 inline-block hover:underline">Clear Search</button>
            @endif
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $teams->links() }}
</div>
