<x-layouts.app>
    <x-slot name="title">Teams</x-slot>

    <div class="space-y-8" x-data="{ 
        search: '{{ request('q') }}',
        loading: false,
        html: '',
        fetchTeams() {
            this.loading = true;
            const url = new URL('{{ route('teams') }}');
            if (this.search) url.searchParams.set('q', this.search);
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.text())
            .then(data => {
                this.html = data;
                this.loading = false;
            });
        }
    }" x-init="$watch('search', () => fetchTeams())">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-display font-black tracking-tight uppercase">Teams</h1>
            
            <div class="flex items-center gap-4">
                <div class="relative group">
                    <input type="text" x-model.debounce.500ms="search" placeholder="Search teams..." 
                           class="bg-white/5 border border-white/10 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-medium focus:border-primary/50 focus:ring-0 transition-all w-64">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2">
                        <svg x-show="!loading" class="w-4 h-4 text-white/30 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <svg x-show="loading" class="animate-spin h-4 w-4 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>

                @auth
                    @if(auth()->user()->hasRole('player'))
                        <a href="{{ route('teams.create') }}" class="btn-primary px-6 py-3 text-xs font-bold tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            CREATE TEAM
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        <div id="team-grid-container" x-html="html || $el.innerHTML">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($teams as $team)
                    <div class="glass p-6 rounded-3xl border border-white/5 hover:border-primary/30 transition-all group">
                        <div class="flex items-center gap-4 mb-4">
                            <img src="{{ $team->logo_url }}" class="w-14 h-14 rounded-2xl object-cover border border-white/10">
                            <div>
                                <h3 class="font-black uppercase tracking-tight">{{ $team->name }}</h3>
                                <p class="text-xs opacity-50 mt-0.5">{{ $team->members_count }}/6 Members</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-white/5">
                            <span class="text-[10px] font-bold uppercase tracking-widest {{ $team->members_count < 6 ? 'text-success' : 'text-danger' }}">
                                {{ $team->members_count < 6 ? 'Recruiting' : 'Full' }}
                            </span>
                            @auth
                                @if(auth()->user()->hasRole('player') && $team->members_count < 6)
                                    @if($team->hasPendingJoinRequest())
                                        <span class="px-4 py-2 bg-warning/10 text-warning border border-warning/20 rounded-xl text-xs font-bold uppercase tracking-widest">Pending</span>
                                    @else
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
                                @endif
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-full glass p-12 rounded-3xl border border-white/5 text-center opacity-40">
                        <p class="text-sm font-medium uppercase tracking-widest">No teams found matching your search</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $teams->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
