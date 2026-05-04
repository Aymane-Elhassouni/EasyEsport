@props(['tournament'])

@php
    $data = \App\Presenters\TournamentPresenter::make($tournament);
@endphp

<div class="glass p-8 rounded-[2.5rem] border border-white/5 group hover:border-primary/20 transition-all relative overflow-hidden">
    <!-- Header: Status -->
    <div class="flex items-start justify-between mb-6">
        <div class="w-14 h-14 rounded-2xl bg-dark-bg border border-white/10 p-3 shrink-0 shadow-2xl">
             <img src="{{ $data->game_logo }}" class="w-full h-full opacity-80 group-hover:opacity-100 transition-opacity">
        </div>
        <div>
            <span class="px-3 py-1 bg-{{ $data->status_color }}/10 text-{{ $data->status_color }} text-[10px] font-black rounded-lg border border-{{ $data->status_color }}/20 uppercase tracking-[0.2em] shadow-neon-{{ $data->status_color }}">
                {{ $data->status_label }}
            </span>
        </div>
    </div>

    <!-- Title Info -->
    <div class="space-y-1 mb-8 relative z-10">
        <h3 class="text-2xl font-display font-black tracking-tight group-hover:text-primary transition-colors line-clamp-1 leading-none uppercase">{{ $data->name }}</h3>
        <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">PRO LEAGUE • {{ $data->game_name }}</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="bg-white/5 p-4 rounded-2xl border border-white/5">
            <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest mb-1">Prize Pool</p>
            <p class="text-xl font-black text-warning">{{ $data->prize_pool }}</p>
        </div>
        <div class="bg-white/5 p-4 rounded-2xl border border-white/5">
            <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest mb-1">Participants</p>
            <p class="text-xl font-black">{{ $data->participants }}<span class="text-xs opacity-40">/{{ $data->max_participants }}</span></p>
        </div>
    </div>

    <!-- Footer -->
    <div class="flex items-center justify-between gap-4 pt-6 border-t border-white/5">
        <div class="flex flex-col">
            <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">{{ $tournament->status === 'completed' ? 'Ended' : 'Starts' }}</p>
            <p class="text-sm font-black">{{ $data->date_human }}</p>
        </div>
        <a href="{{ route('tournaments.show', $tournament->id) }}" class="btn-primary py-3 px-8 text-[10px] font-black tracking-widest shadow-neon-primary">
            {{ $tournament->status === 'pending' ? 'JOIN NOW' : 'VIEW DETAILS' }}
        </a>
    </div>

    <!-- Ambient Gradient Hover -->
    <div class="absolute -right-16 -top-16 w-48 h-48 bg-primary/5 blur-3xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
</div>
