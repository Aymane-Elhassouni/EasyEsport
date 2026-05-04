<div class="relative glass rounded-[3.5rem] p-10 md:p-14 overflow-hidden border border-white/5 shadow-2xl">
    <!-- Ambient backgrounds -->
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-primary/10 blur-[120px] rounded-full translate-x-1/3 -translate-y-1/3"></div>
    
    <div class="flex flex-col md:flex-row items-center gap-10 md:gap-14 relative z-10">
        <div class="w-32 h-32 md:w-40 md:h-40 bg-dark-bg p-6 rounded-[2.5rem] border border-white/10 shadow-2xl shrink-0 group transition-all duration-700 hover:rotate-6">
            <img src="{{ $data->game_logo }}" class="w-full h-full opacity-80 group-hover:opacity-100 transition-opacity">
        </div>

        <div class="flex-1 text-center md:text-left space-y-6">
            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-{{ $data->status_color }}/10 text-{{ $data->status_color }} text-[10px] font-black rounded-lg border border-{{ $data->status_color }}/20 uppercase tracking-[0.2em] shadow-neon-{{ $data->status_color }}">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-{{ $data->status_color }} opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-{{ $data->status_color }}"></span>
                    </span>
                    {{ $data->status_label }}
                </div>
                <h1 class="text-4xl md:text-6xl font-display font-black tracking-tighter leading-none uppercase">{{ $tournament->name }}</h1>
                <div class="flex flex-wrap justify-center md:justify-start gap-6 text-[10px] font-black opacity-40 uppercase tracking-[0.2em]">
                    <span class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> {{ $data->participants }} Teams Registered</span>
                    <span class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $data->prize_pool }} Prize Pool</span>
                    <span class="text-secondary">#{{ $tournament->game->slug ?? 'Elite' }} Series</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-4 items-center">
            @auth
                @if($tournament->status === 'Upcoming')
                    @php
                        $isFull = $tournament->registeredTeamsCount >= $tournament->maxTeams;
                    @endphp

                    @if($registrationStatus)
                        @php
                            $statusStyles = [
                                'pending'  => ['bg-warning/10 border-warning/20 text-warning',  'REGISTRATION PENDING'],
                                'approved' => ['bg-success/10 border-success/20 text-success',  'REGISTERED ✓'],
                                'rejected' => ['bg-danger/10  border-danger/20  text-danger',   'REGISTRATION REJECTED'],
                            ];
                            [$style, $label] = $statusStyles[$registrationStatus] ?? $statusStyles['pending'];
                        @endphp
                        <div class="px-8 py-4 rounded-2xl border font-black tracking-[0.2em] text-xs uppercase {{ $style }}">
                            {{ $label }}
                        </div>
                        <p class="text-[10px] font-bold opacity-30 uppercase tracking-widest">Team: <span class="text-white">{{ $userTeam?->name }}</span></p>

                    @elseif(!$userTeam)
                        <button disabled class="glass opacity-40 cursor-not-allowed px-10 py-4 font-black tracking-[0.2em] text-xs border border-white/10 uppercase rounded-2xl">
                            YOU NEED A TEAM
                        </button>

                    @elseif(Auth::id() !== $userTeam->captain_id)
                        <button disabled class="glass opacity-40 cursor-not-allowed px-10 py-4 font-black tracking-[0.2em] text-xs border border-white/10 uppercase rounded-2xl">
                            ONLY CAPTAINS CAN REGISTER
                        </button>

                    @elseif($isFull)
                        <button disabled class="glass opacity-40 cursor-not-allowed px-10 py-4 font-black tracking-[0.2em] text-xs border border-white/10 uppercase rounded-2xl">
                            TOURNAMENT FULL
                        </button>

                    @else
                        <form action="{{ route('tournaments.register', $tournament->slug) }}" method="POST">
                            @csrf
                            <input type="hidden" name="team_id" value="{{ $userTeam->id }}">
                            <button class="btn-primary shadow-neon-primary px-12 py-5 font-black tracking-[0.2em] text-xs transform hover:scale-110 transition-all uppercase">
                                JOIN CHAMPIONSHIP
                            </button>
                        </form>
                        <p class="text-[10px] font-bold opacity-30 uppercase tracking-widest">Registering as: <span class="text-white">{{ $userTeam->name }}</span></p>
                    @endif

                    @if(auth()->user()?->hasRole('admin') || auth()->user()?->hasRole('super_admin'))
                        <div class="mt-4 border-t border-white/5 pt-4 w-full flex flex-col items-center">
                            <form action="{{ route('admin.tournaments.launch', $tournament->slug) }}" method="POST">
                                @csrf
                                <button class="px-8 py-3 bg-primary text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-xl shadow-neon-primary hover:scale-105 transition-all">
                                    🚀 Launch Tournament
                                </button>
                            </form>
                            <p class="text-[9px] font-bold text-gray-500 mt-2 uppercase tracking-widest text-center">Admin: Starts matches & locks entries</p>
                            @if($tournament->status === 'Live' && $tournament->has_group_stage && $isGroupStageCompleted)
                                <form action="{{ route('admin.tournaments.knockout', $tournament->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button class="px-8 py-3 bg-secondary text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-xl shadow-neon-secondary hover:scale-105 transition-all">
                                        🏁 Generate Knockout Bracket
                                    </button>
                                </form>
                                <p class="text-[9px] font-bold text-gray-500 mt-2 uppercase tracking-widest text-center">Group stage is finished. Ready for brackets!</p>
                            @endif
                        </div>
                    @endif

                @else
                    <button class="btn-primary shadow-neon-primary px-12 py-4 font-black tracking-[0.2em] text-xs uppercase animate-pulse">
                        WATCH BROADCAST
                    </button>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-primary shadow-neon-primary px-12 py-5 font-black tracking-[0.2em] text-xs uppercase">
                    LOGIN TO JOIN
                </a>
            @endauth
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex items-center justify-center md:justify-start gap-12 mt-16 border-t border-white/5 pt-8 overflow-x-auto">
        @php
            $tabs = ['announcements' => 'Announcements'];
            if($tournament->has_group_stage) {
                $tabs['standings'] = 'Groups & Standings';
            }
            $tabs = array_merge($tabs, ['brackets' => 'Tournament Brackets', 'teams' => 'Registered Teams', 'rules' => 'Rules & Information']);
        @endphp
        @foreach($tabs as $key => $label)
            <button @click="activeTab = '{{ $key }}'" 
                    :class="activeTab === '{{ $key }}' ? 'text-primary border-primary' : 'text-gray-500 border-transparent'"
                    class="pb-6 text-[10px] font-black uppercase tracking-[0.3em] border-b-2 transition-all hover:text-primary whitespace-nowrap">
                {{ $label }}
            </button>
        @endforeach
        @if(auth()->user()?->hasRole('admin') || auth()->user()?->hasRole('super_admin'))
            <button @click="activeTab = 'registrations'"
                    :class="activeTab === 'registrations' ? 'text-warning border-warning' : 'text-gray-500 border-transparent'"
                    class="pb-6 text-[10px] font-black uppercase tracking-[0.3em] border-b-2 transition-all hover:text-warning whitespace-nowrap">
                Registrations
                @if($tournament->pending_registrations_count > 0)
                    <span class="ml-1 px-1.5 py-0.5 bg-warning/20 text-warning rounded-md text-[9px]">{{ $tournament->pending_registrations_count }}</span>
                @endif
            </button>
        @endif
    </div>
</div>
