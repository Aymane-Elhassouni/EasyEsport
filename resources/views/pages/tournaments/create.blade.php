<x-layouts.app title="New Tournament">

    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-display font-black uppercase tracking-tight">
                New <span class="text-primary">Tournament</span>
            </h1>
            <x-ui.button href="{{ route('tournaments') }}" variant="ghost" size="sm">← Back</x-ui.button>
        </div>

        <x-ui.card>
            <form method="POST" action="{{ route('admin.tournaments.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Tournament Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors"
                           placeholder="e.g. Valorant Elite Season 5">
                    @error('name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Game</label>
                    <select name="game_id"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors">
                        <option value="">Select a game</option>
                        @foreach($games as $game)
                            <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                                {{ $game->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('game_id') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Format</label>
                    <select name="format"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors">
                        <option value="">Select format</option>
                        @foreach(['single_elimination' => 'Single Elimination', 'double_elimination' => 'Double Elimination', 'league' => 'League', 'round_robin' => 'Round Robin'] as $val => $label)
                            <option value="{{ $val }}" {{ old('format') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('format') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Max Teams</label>
                        <input type="number" name="max_teams" value="{{ old('max_teams', 8) }}" min="2"
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors">
                        @error('max_teams') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Players/Team</label>
                        <input type="number" name="players_per_team" value="{{ old('players_per_team', 5) }}" min="1"
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors">
                        @error('players_per_team') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
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

                <x-ui.button type="submit" variant="primary" class="w-full justify-center">
                    Create Tournament
                </x-ui.button>
            </form>
        </x-ui.card>
    </div>

</x-layouts.app>
