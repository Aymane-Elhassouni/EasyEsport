<x-layouts.app title="Tournaments">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-display font-black uppercase tracking-tight">
                All <span class="text-primary">Tournaments</span>
            </h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">
                {{ $tournaments->total() ?? 0 }} tournaments available
            </p>
        </div>

        @if(auth()->user()->role?->name === 'admin' || auth()->user()->role?->name === 'super_admin')
            <x-ui.button variant="primary" size="sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Tournament
            </x-ui.button>
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
                    @if($tournament->status ?? false)
                        <span class="absolute top-3 right-3 px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                            {{ $tournament->status === 'active' ? 'bg-success/20 text-success border border-success/30' : 'bg-gray-500/20 text-gray-400 border border-gray-500/30' }}">
                            {{ $tournament->status }}
                        </span>
                    @endif
                </div>

                <div class="p-5 space-y-4">
                    <div>
                        <h3 class="font-display font-bold text-base truncate">{{ $tournament->name }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $tournament->game ?? '—' }}</p>
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
    @if(isset($tournaments) && $tournaments->hasPages())
        <div class="mt-8">
            {{ $tournaments->links() }}
        </div>
    @endif

</x-layouts.app>
