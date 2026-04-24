<x-layouts.app title="Player Dashboard">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-widest">Welcome back,</p>
            <h1 class="text-2xl font-display font-black uppercase tracking-tight">
                {{ auth()->user()->firstname ?? 'Player' }}
                <span class="text-primary">{{ auth()->user()->lastname ?? '' }}</span>
            </h1>
        </div>
        <x-ui.button href="{{ route('upload') }}" variant="primary" size="sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
            </svg>
            Scan Result
        </x-ui.button>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @php
            $profile = auth()->user()->profile;
            $wins    = $profile->wins   ?? 0;
            $losses  = $profile->losses ?? 0;
            $total   = $wins + $losses;
            $rate    = $total > 0 ? round(($wins / $total) * 100) : 0;
        @endphp

        @foreach([
            ['label' => 'Global Rank',  'value' => $profile->rank ?? 'Unranked', 'color' => 'primary'],
            ['label' => 'Win Rate',     'value' => $rate . '%',                  'color' => 'success'],
            ['label' => 'Total Wins',   'value' => $wins,                        'color' => 'secondary'],
            ['label' => 'Total Losses', 'value' => $losses,                      'color' => 'danger'],
        ] as $stat)
            <x-ui.card>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">{{ $stat['label'] }}</p>
                <p class="text-3xl font-display font-black mt-1 text-{{ $stat['color'] }}">{{ $stat['value'] }}</p>
            </x-ui.card>
        @endforeach
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Recent Matches --}}
        <div class="lg:col-span-2">
            @include('dashboard.partials.recent-activities')
        </div>

        {{-- Sidebar: Tournaments + OCR --}}
        <div class="space-y-6">
            <x-ui.card title="Trending Tournaments"
                       icon='<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>'>
                <div class="space-y-3">
                    @forelse($tournaments ?? [] as $tournament)
                        <a href="{{ route('tournaments.show', $tournament) }}"
                           class="flex items-center justify-between p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 hover:border-primary/20 transition-all group">
                            <div>
                                <p class="text-sm font-bold truncate">{{ $tournament->name }}</p>
                                <p class="text-[10px] text-gray-400 uppercase font-bold mt-0.5">{{ $tournament->game ?? '—' }}</p>
                            </div>
                            <span class="text-warning text-xs font-black shrink-0 ml-2">{{ $tournament->prize_pool ?? '' }}</span>
                        </a>
                    @empty
                        <p class="text-xs text-gray-500 text-center py-4">No tournaments available.</p>
                    @endforelse
                </div>
                <div class="mt-4">
                    <x-ui.button href="{{ route('tournaments') }}" variant="ghost" size="sm" class="w-full justify-center">
                        View All
                    </x-ui.button>
                </div>
            </x-ui.card>

            {{-- OCR CTA --}}
            <x-ui.card>
                <div class="text-center space-y-3">
                    <div class="w-14 h-14 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mx-auto">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">Scan Match Result</p>
                        <p class="text-xs text-gray-400 mt-0.5">Validate your score with OCR</p>
                    </div>
                    <x-ui.button href="{{ route('upload') }}" variant="primary" class="w-full justify-center">
                        Upload Image
                    </x-ui.button>
                </div>
            </x-ui.card>
        </div>

    </div>

</x-layouts.app>
