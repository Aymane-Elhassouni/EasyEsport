<div class="space-y-6" x-data="{ open: false }">
    <h2 class="text-xl font-display font-black tracking-tight px-2">Active Members ({{ $team->members->count() }}/6)</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        {{-- Captain Item --}}
        <div class="glass p-6 rounded-3xl border border-primary/20 flex items-center justify-between group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-primary/5 blur-xl"></div>
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img class="w-12 h-12 rounded-xl bg-dark-bg object-cover" src="{{ $team->captain->avatar_url }}">
                    <span class="absolute bottom-0 left-0 w-3 h-3 rounded-full border-2 border-dark-bg {{ $team->captain->isOnline() ? 'bg-success' : 'bg-danger' }}"></span>
                    <div class="absolute -top-1 -right-1 w-5 h-5 bg-warning text-white rounded-md flex items-center justify-center shadow-lg border-2 border-dark-bg">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-sm">{{ $team->captain->firstname }} {{ $team->captain->lastname }} @if(Auth::id() === $team->captain_id) <span class="text-xs text-primary">(You)</span> @endif</h4>
                    <p class="text-[10px] font-bold text-primary uppercase tracking-widest">Captain / Founder</p>
                </div>
            </div>
        </div>

        {{-- Members Loop --}}
        @foreach($team->members as $member)
            @if($member->user_id !== $team->captain_id)
                <div class="glass p-6 rounded-3xl border border-white/5 flex items-center justify-between group hover:border-primary/30 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <img class="w-12 h-12 rounded-xl bg-dark-bg object-cover" src="{{ $member->user->avatar_url }}">
                            <span class="absolute bottom-0 left-0 w-3 h-3 rounded-full border-2 border-dark-bg {{ $member->user->isOnline() ? 'bg-success' : 'bg-danger' }}"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">{{ $member->user->firstname }} {{ $member->user->lastname }} @if(Auth::id() === $member->user_id) <span class="text-xs text-primary">(You)</span> @endif</h4>
                            <p class="text-[10px] font-bold opacity-50 uppercase tracking-widest">Active Member</p>
                        </div>
                    </div>

                    @if(Auth::id() === $team->captain_id)
                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button type="button"
                                @click="
                                    fetch('{{ route('teams.kick', ['team' => $team->slug, 'user' => $member->user_id]) }}', {
                                        method: 'DELETE',
                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                                    }).then(r => r.ok && $el.closest('.group').remove())
                                "
                                class="p-2 hover:bg-danger/20 rounded-lg text-danger transition-colors" title="Kick member">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path></svg>
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
        
        {{-- Open Slots --}}
        @for($i = $team->members->count(); $i < 6; $i++)
            <div @click="@if(Auth::id() === $team->captain_id) open = true; $nextTick(() => $dispatch('load-players')) @endif"
                class="border-2 border-dashed border-white/5 p-6 rounded-3xl flex items-center justify-center group {{ Auth::id() === $team->captain_id ? 'hover:border-primary/50 cursor-pointer' : '' }} transition-all">
                <div class="text-center">
                    <div class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center text-white/20 group-hover:bg-primary group-hover:text-white transition-all mx-auto mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <p class="text-[10px] font-bold opacity-30 uppercase tracking-widest group-hover:opacity-100 group-hover:text-primary transition-all">Empty Slot</p>
                </div>
            </div>
        @endfor
    </div>

    {{-- Invite Modal --}}
    @if(Auth::id() === $team->captain_id)
    <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/60" @click.self="open = false">
        <div class="glass border border-white/10 rounded-3xl p-8 w-full max-w-md space-y-6"
             x-data="{
                query: '',
                players: [],
                selected: null,
                loading: false,
                async search() {
                    this.loading = true;
                    const res = await fetch('{{ route('teams.players.search', $team->slug) }}?q=' + encodeURIComponent(this.query));
                    this.players = await res.json();
                    this.loading = false;
                },
                select(player) { this.selected = player; this.query = player.name; this.players = []; }
             }"
             x-init="search()"
             @load-players.window="search()">
            <h3 class="text-lg font-black uppercase tracking-widest">Invite Player</h3>

            <form action="{{ route('teams.invite', $team->slug) }}" method="POST" class="space-y-4">
                @csrf
                <div class="relative">
                    <input type="text" x-model="query" @input.debounce.300ms="search()"
                        placeholder="Search by name or email..."
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-3 text-sm focus:outline-none focus:border-primary transition-all">
                    <input type="hidden" name="email" :value="selected ? selected.email : query">

                    <div x-show="players.length > 0" class="mt-2 w-full bg-dark-bg border border-white/10 rounded-2xl overflow-hidden z-10 max-h-48 overflow-y-auto">
                        <template x-for="player in players" :key="player.id">
                            <div @click="select(player)" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 cursor-pointer transition-all">
                                <img :src="player.avatar" class="w-8 h-8 rounded-lg object-cover">
                                <div>
                                    <p class="text-sm font-bold" x-text="player.name"></p>
                                    <p class="text-xs opacity-50" x-text="player.email"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="query.length > 1 && players.length === 0 && !loading" class="mt-2 w-full bg-dark-bg border border-white/10 rounded-2xl px-4 py-3 z-10">
                        <p class="text-xs opacity-50">No platform user found — we'll send an email invite.</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 btn-primary py-3 text-xs font-bold tracking-widest">SEND INVITE</button>
                    <button type="button" @click="open = false; query = ''; selected = null; players = []"
                        class="px-6 py-3 glass border border-white/10 rounded-2xl text-xs font-bold hover:bg-white/5 transition-all">CANCEL</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
