<x-layouts.app title="Tournaments">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-display font-black uppercase tracking-tight">
                All <span class="text-primary">Tournaments</span>
            </h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">
                {{ $tournaments->count() }} tournaments available
            </p>
        </div>

        @if(auth()->user()?->hasRole('admin') || auth()->user()?->hasRole('super_admin'))
            <x-ui.modal id="new-tournament-modal" title="New Tournament" size="lg">
                <x-slot:trigger>
                    <x-ui.button variant="primary" size="sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Tournament
                    </x-ui.button>
                </x-slot:trigger>

                <form id="createTournamentForm" method="POST" action="{{ route('admin.tournaments.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Tournament Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors"
                               placeholder="e.g. Valorant Elite Season 5">
                        @error('name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Game</label>
                            <div x-data="{ open: false, selected: '{{ old('game_id') }}', label: '{{ old('game_id') ? $games->find(old('game_id'))?->name : 'Select a game' }}' }" class="relative">
                                <input type="hidden" name="game_id" :value="selected">
                                <button type="button" @click="open = !open"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-left flex items-center justify-between hover:border-primary/50 transition-colors">
                                    <span x-text="label" class="truncate"></span>
                                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-cloak
                                     class="absolute z-50 mt-2 w-full bg-dark-bg border border-white/10 rounded-xl overflow-hidden shadow-xl">
                                    @foreach($games as $game)
                                        <button type="button"
                                                @click="selected = '{{ $game->id }}'; label = '{{ $game->name }}'; open = false"
                                                class="w-full px-4 py-3 text-sm text-left hover:bg-primary/10 hover:text-primary transition-colors"
                                                :class="selected == '{{ $game->id }}' ? 'text-primary bg-primary/5' : 'text-gray-300'">
                                            {{ $game->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            @error('game_id') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Format</label>
                            <div x-data="{ open: false, selected: '{{ old('format') }}', label: '{{ old('format') ?: 'Select format' }}' }" class="relative">
                                <input type="hidden" name="format" :value="selected">
                                <button type="button" @click="open = !open"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-left flex items-center justify-between hover:border-primary/50 transition-colors">
                                    <span x-text="label" class="truncate"></span>
                                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-cloak
                                     class="absolute z-50 mt-2 w-full bg-dark-bg border border-white/10 rounded-xl overflow-hidden shadow-xl">
                                    @foreach(['single_elimination' => 'Single Elimination', 'double_elimination' => 'Double Elimination', 'league' => 'League', 'round_robin' => 'Round Robin'] as $val => $lbl)
                                        <button type="button"
                                                @click="selected = '{{ $val }}'; label = '{{ $lbl }}'; open = false"
                                                class="w-full px-4 py-3 text-sm text-left hover:bg-primary/10 hover:text-primary transition-colors"
                                                :class="selected == '{{ $val }}' ? 'text-primary bg-primary/5' : 'text-gray-300'">
                                            {{ $lbl }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            @error('format') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Max Teams</label>
                            <input type="number" name="max_teams" value="{{ old('max_teams', 8) }}" min="2"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors">
                            @error('max_teams') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Players / Team</label>
                            <input type="number" name="players_per_team" value="{{ old('players_per_team', 5) }}" min="1"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors">
                            @error('players_per_team') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div x-data="{ hasGroups: false }" class="space-y-4">
                        <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="has_group_stage" value="1" x-model="hasGroups" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                <span class="ml-3 text-xs font-bold uppercase tracking-widest text-gray-300">Enable Group Stage (Hybrid)</span>
                            </label>

                            <div x-show="hasGroups" x-transition class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-white/5">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 mb-2">Teams Per Group</label>
                                    <input type="number" name="teams_per_group" value="4" min="2"
                                           class="w-full bg-dark-bg border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 mb-2">Qualifiers Per Group</label>
                                    <input type="number" name="qualifiers_per_group" value="2" min="1"
                                           class="w-full bg-dark-bg border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Start Date</label>
                            <input type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors">
                            @error('start_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">End Date</label>
                            <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors">
                            @error('end_date') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <x-slot:footer>
                        <x-ui.button type="submit" variant="primary" form="createTournamentForm">Create Tournament</x-ui.button>
                    </x-slot:footer>
                </form>
            </x-ui.modal>
        @endif
    </div>

    {{-- Tournament Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($tournaments as $tournament)
            <x-ui.card :noPad="true">
                {{-- Banner --}}
                <div class="h-32 bg-gradient-to-br from-primary/30 to-secondary/10 rounded-t-3xl flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-grid-white opacity-10"></div>
                    <span class="relative z-10 text-4xl font-display font-black text-white/20 uppercase tracking-widest">
                        {{ substr($tournament->name, 0, 2) }}
                    </span>
                    @if($tournament->status)
                        <span class="absolute top-3 right-3 px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-white/10 {{ $tournament->getStatusBadgeClass() }}">
                            {{ $tournament->status }}
                        </span>
                    @endif
                </div>

                <div class="p-5 space-y-4">
                    <div>
                        <h3 class="font-display font-bold text-base truncate">{{ $tournament->name }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $tournament->game_name ?? '—' }}</p>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-400">Prize Pool</span>
                        <span class="font-black text-warning">{{ $tournament->prize_pool ?? 'TBD' }}</span>
                    </div>

                    <x-ui.button href="{{ route('tournaments.show', $tournament) }}"
                                 variant="secondary"
                                 size="sm"
                                 class="w-full justify-center">
                        View Details
                    </x-ui.button>
                </div>
            </x-ui.card>
        @empty
            <div class="col-span-full text-center py-16 text-gray-500">
                <p class="text-lg font-bold">No tournaments yet.</p>
                <p class="text-sm mt-1">Check back soon for upcoming events.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}


</x-layouts.app>
