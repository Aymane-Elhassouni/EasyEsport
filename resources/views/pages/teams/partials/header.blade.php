<div class="relative glass rounded-[3rem] p-10 border border-white/5 overflow-hidden">
    <!-- Background Glows -->
    <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-primary/10 blur-[100px] rounded-full translate-x-1/4 -translate-y-1/4"></div>
    
    <div class="flex flex-col md:flex-row items-center gap-10 relative z-10">
        <div class="relative group">
            <div class="w-32 h-32 bg-dark-bg rounded-[2.5rem] p-4 border border-white/10 shadow-2xl relative z-10">
                <img src="{{ $team->logo_url }}" class="w-full h-full object-cover rounded-[2rem]" alt="team logo">
            </div>
            @if(Auth::id() === $team->captain_id)
                <form action="{{ route('teams.update', $team->id) }}" method="POST" enctype="multipart/form-data" id="logo-form">
                    @csrf @method('PATCH')
                    <input type="file" name="logo" id="logo-input" class="hidden" accept="image/*"
                        onchange="document.getElementById('logo-form').submit()">
                </form>
                <button onclick="document.getElementById('logo-input').click()" type="button"
                    class="absolute -bottom-2 -right-2 w-10 h-10 bg-primary text-white rounded-xl flex items-center justify-center shadow-neon-primary hover:scale-110 transition-transform z-20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                </button>
            @endif
        </div>

        <div class="flex-1 text-center md:text-left space-y-4">
            <div>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                    <h1 class="text-4xl font-display font-black tracking-tight leading-none uppercase">{{ $team->name }}</h1>
                    <span class="px-3 py-1 bg-primary/10 text-primary border border-primary/20 rounded-lg text-xs font-black tracking-widest">[{{ strtoupper(substr($team->name, 0, 3)) }}]</span>
                </div>
                <p class="text-gray-500 font-medium mt-1">Professional Esport Club • Ranked <span class="text-white">#{{ rand(1, 50) }} Global</span></p>
            </div>

            <div class="flex flex-wrap justify-center md:justify-start gap-8">
                <div class="text-center md:text-left">
                    <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest mb-1">Win Rate</p>
                    <p class="text-xl font-black text-success">{{ $team->winRate }}</p>
                </div>
                <div class="text-center md:text-left">
                    <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest mb-1">Total Earnings</p>
                    <p class="text-xl font-black text-warning">{{ $team->earnings }}</p>
                </div>
                <div class="text-center md:text-left">
                    <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest mb-1">Elo Rating</p>
                    <p class="text-xl font-black text-secondary">{{ $team->elo }}</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            @if(Auth::id() === $team->captain_id)
                <button class="btn-primary flex items-center gap-2 px-8 py-3 text-xs font-bold tracking-widest shadow-neon-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    RECRUIT PLAYERS
                </button>
            @else
                <button class="glass px-8 py-3 text-xs font-bold tracking-widest border-white/10 hover:bg-white/5 transition-all">LEAVE TEAM</button>
            @endif
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex items-center justify-center md:justify-start gap-10 mt-12 border-t border-white/5 pt-6">
        @foreach(['roster' => 'Team Roster', 'requests' => 'Join Requests', 'settings' => 'Team Settings'] as $key => $label)
            @if($key !== 'settings' || Auth::id() === $team->captain_id)
                <button @click="activeTab = '{{ $key }}'" 
                        :class="activeTab === '{{ $key }}' ? 'text-primary border-primary' : 'text-gray-500 border-transparent'"
                        class="pb-4 text-xs font-black uppercase tracking-[0.2em] border-b-2 transition-all hover:text-primary">
                    {{ $label }}
                </button>
            @endif
        @endforeach
    </div>
</div>
