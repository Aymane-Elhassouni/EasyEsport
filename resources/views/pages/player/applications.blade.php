<x-layouts.app title="My Applications">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-display font-black uppercase tracking-tight">
                My <span class="text-primary">Applications</span>
            </h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">
                {{ $applications->count() }} applications · {{ $team?->name ?? 'No team' }}
            </p>
        </div>
        <x-ui.button href="{{ route('tournaments') }}" variant="primary" size="sm">
            Browse Tournaments
        </x-ui.button>
    </div>

    @if(!$team)
        <div class="glass p-16 rounded-3xl border border-white/5 text-center opacity-40">
            <p class="text-sm font-bold uppercase tracking-widest">You need a team to apply to tournaments</p>
        </div>
    @else
        <div class="space-y-5">
            @forelse($applications as $app)
                @with($cfg = $statusConfig[$app->status] ?? $statusConfig['pending'])
                <div class="glass rounded-3xl border border-white/5 overflow-hidden">

                    {{-- Announcement Header --}}
                    <div class="flex items-center justify-between px-6 py-4 border-b border-white/5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-{{ $cfg['color'] }}/10 border border-{{ $cfg['color'] }}/20 flex items-center justify-center text-lg">
                                {{ $cfg['icon'] }}
                            </div>
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-{{ $cfg['color'] }}">{{ $cfg['label'] }}</p>
                                <p class="text-[10px] text-gray-500">{{ $app->created_at?->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Application #{{ $app->id }}</span>
                    </div>

                    {{-- Announcement Body --}}
                    <div class="p-6 flex items-center gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-dark-bg border border-white/10 flex items-center justify-center shrink-0 p-2">
                            <img src="{{ $app->tournament->game?->logo ?? 'https://api.dicebear.com/7.x/identicon/svg?seed=' . $app->tournament->name }}"
                                 class="w-full h-full object-contain opacity-80">
                        </div>
                        <div class="flex-1">
                            <h3 class="font-display font-black text-lg uppercase tracking-tight">{{ $app->tournament->name }}</h3>
                            <div class="flex items-center gap-4 mt-1 text-xs text-gray-400">
                                <span>{{ $app->tournament->game?->name ?? '—' }}</span>
                                <span>·</span>
                                <span>{{ ucfirst(str_replace('_', ' ', $app->tournament->format)) }}</span>
                                <span>·</span>
                                <span>Starts {{ $app->tournament->start_date?->format('d M Y') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('tournaments.show', $app->tournament->slug) }}"
                           class="px-5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-xs font-bold hover:border-primary/40 hover:text-primary transition-all shrink-0">
                            View →
                        </a>
                    </div>
                </div>
                @endwith
            @empty
                <div class="glass p-16 rounded-3xl border border-white/5 text-center opacity-40">
                    <p class="text-sm font-bold uppercase tracking-widest">No applications yet</p>
                    <p class="text-xs mt-2">Browse tournaments and apply with your team</p>
                </div>
            @endforelse
        </div>
    @endif

</x-layouts.app>
