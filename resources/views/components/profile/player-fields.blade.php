{{--
    Player-specific profile fields.
    Props: $user (App\Models\User with ->profile relation)
--}}
@props(['user'])

@php $profile = $user->profile; @endphp

<div class="space-y-6">

    {{-- Section: Gaming Identity --}}
    <x-ui.card title="Gaming Identity"
               icon='<path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>'>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Username</label>
                <p class="text-sm font-semibold text-primary">{{ $user->username ?? $user->firstname ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Game Tag</label>
                <p class="text-sm font-semibold">{{ $profile->game_tag ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Main Game</label>
                <p class="text-sm font-semibold">{{ $profile->main_game ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Rank</label>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-primary/10 text-primary text-xs font-black border border-primary/20">
                    {{ $profile->rank ?? 'Unranked' }}
                </span>
            </div>
        </div>
    </x-ui.card>

    {{-- Section: Stats --}}
    <x-ui.card title="Performance Stats"
               icon='<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'>
        <div class="grid grid-cols-3 gap-4 text-center">
            @php
                $wins   = $profile->wins ?? 0;
                $losses = $profile->losses ?? 0;
                $total  = $wins + $losses;
                $rate   = $total > 0 ? round(($wins / $total) * 100) : 0;
            @endphp
            <div class="p-4 rounded-2xl bg-success/5 border border-success/20">
                <p class="text-2xl font-display font-black text-success">{{ $wins }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-1">Wins</p>
            </div>
            <div class="p-4 rounded-2xl bg-danger/5 border border-danger/20">
                <p class="text-2xl font-display font-black text-danger">{{ $losses }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-1">Losses</p>
            </div>
            <div class="p-4 rounded-2xl bg-primary/5 border border-primary/20">
                <p class="text-2xl font-display font-black text-primary">{{ $rate }}%</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-1">Win Rate</p>
            </div>
        </div>

        {{-- Win rate bar --}}
        <div class="mt-4">
            <div class="flex justify-between text-xs text-gray-400 mb-1">
                <span>Win Rate Progress</span>
                <span class="font-bold text-primary">{{ $rate }}%</span>
            </div>
            <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-primary to-secondary rounded-full shadow-neon-primary transition-all duration-1000"
                     style="width: {{ $rate }}%"></div>
            </div>
        </div>
    </x-ui.card>

    {{-- Section: Player Actions --}}
    <x-ui.card title="Quick Actions"
               icon='<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>'>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-ui.button href="{{ route('tournaments') }}" variant="primary" class="w-full justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                Browse Tournaments
            </x-ui.button>
            <x-ui.button href="{{ route('teams') }}" variant="secondary" class="w-full justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                My Teams
            </x-ui.button>
        </div>
    </x-ui.card>

</div>
