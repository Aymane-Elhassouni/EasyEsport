<x-layouts.app :title="$data->title">

    {{-- Back button --}}
    <div class="mb-6">
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-white transition-colors uppercase tracking-widest">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
    </div>

    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Banner --}}
        @if($data->bannerUrl)
            <div class="relative h-64 rounded-3xl overflow-hidden">
                <img src="{{ $data->bannerUrl }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-5 left-6">
                    <span class="px-3 py-1 rounded-lg bg-primary/20 border border-primary/30 text-primary text-[10px] font-black uppercase tracking-widest backdrop-blur-sm">
                        {{ $data->tournamentName }}
                    </span>
                </div>
            </div>
        @endif

        {{-- Main Card --}}
        <div class="glass rounded-3xl border border-white/5 overflow-hidden">

            {{-- Header --}}
            <div class="px-8 py-6 border-b border-white/5">
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-3 flex-1">
                        @if(!$data->bannerUrl)
                            <span class="px-3 py-1 rounded-lg bg-primary/10 border border-primary/20 text-primary text-[10px] font-black uppercase tracking-widest">
                                {{ $data->tournamentName }}
                            </span>
                        @endif
                        <h1 class="text-2xl font-display font-black uppercase tracking-tight">{{ $data->title }}</h1>
                        <div class="flex items-center gap-4 text-xs text-gray-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ $data->authorName }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $data->timeAgo }}
                            </span>
                            @if($data->gameName !== '—')
                                <span class="flex items-center gap-1.5">
                                    @if($data->gameLogo)
                                        <img src="{{ $data->gameLogo }}" class="w-4 h-4 rounded opacity-60">
                                    @endif
                                    {{ $data->gameName }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @if(auth()->user()?->hasRole('admin') || auth()->user()?->hasRole('super_admin'))
                        <span class="flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-widest border shrink-0
                            {{ $announcement->status === 'public' ? 'bg-success/10 text-success border-success/20' : 'bg-warning/10 text-warning border-warning/20' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $announcement->status === 'public' ? 'bg-success' : 'bg-warning' }}"></span>
                            {{ $announcement->status }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Body --}}
            <div class="px-8 py-6">
                <p class="text-base text-gray-300 leading-relaxed whitespace-pre-line">{{ $data->body }}</p>
            </div>

            {{-- Tournament Details --}}
            <div class="px-8 py-5 border-t border-white/5">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3">Tournament Details</p>
                <div class="flex flex-wrap gap-3">
                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-white/5 border border-white/10">
                        <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-xs font-bold text-gray-300">Max Teams: <span class="text-white">{{ $data->maxTeams }}</span></span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-white/5 border border-white/10">
                        <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span class="text-xs font-bold text-gray-300">Format: <span class="text-white uppercase">{{ $data->format }}</span></span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-white/5 border border-white/10">
                        <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span class="text-xs font-bold text-gray-300">Players/Team: <span class="text-white">{{ $data->playersPerTeam }}</span></span>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-8 py-5 border-t border-white/5 flex items-center justify-between">
                <a href="{{ route('tournaments.show', $data->tournamentSlug) }}"
                   class="inline-flex items-center gap-2 text-xs font-bold text-primary hover:text-primary/80 transition-colors">
                    View Tournament
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>

                @if(auth()->user()?->hasRole('admin') || auth()->user()?->hasRole('super_admin'))
                    <form method="POST" action="/admin/announcements/{{ $announcement->slug }}/delete"
                          onsubmit="return confirm('Delete this announcement?')">
                        @csrf
                        <button class="px-4 py-2 rounded-xl bg-danger/5 border border-danger/20 text-danger hover:bg-danger hover:text-white text-xs font-bold transition-all">
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

</x-layouts.app>
