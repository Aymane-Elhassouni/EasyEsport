<div class="space-y-8">
    <div class="flex items-center justify-between px-4">
        <h2 class="text-xl font-display font-black tracking-tight uppercase">Legion of Challengers</h2>
        <span class="text-xs font-bold opacity-40 uppercase tracking-widest">{{ $tournament->registrations_count }}/{{ $tournament->max_teams }} Teams Confirmed</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($tournament->registrations->where('status', 'approved') as $reg)
            <div class="glass p-8 rounded-[2.5rem] border border-white/5 hover:border-primary/30 transition-all duration-500 group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 blur-2xl rounded-full opacity-0 group-hover:opacity-100"></div>
                <div class="flex flex-col items-center text-center space-y-6 relative z-10">
                    <div class="w-20 h-20 bg-dark-bg p-4 rounded-3xl border border-white/10 shadow-2xl group-hover:scale-110 transition-transform">
                        <img src="https://api.dicebear.com/7.x/identicon/svg?seed={{ urlencode($reg->team->name) }}" class="opacity-80">
                    </div>
                    <div>
                        <h4 class="text-lg font-black tracking-tight uppercase leading-none mb-1">{{ $reg->team->name }}</h4>
                        <p class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">{{ $reg->team->members->count() }} Members</p>
                    </div>
                    <div class="flex gap-4 opacity-40">
                         <div class="text-[10px] font-black uppercase text-secondary">ELO {{ rand(1800, 3200) }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full glass p-20 rounded-[4rem] text-center opacity-40 border border-white/5">
                <p class="font-black uppercase tracking-widest">No approved teams yet</p>
            </div>
        @endforelse
    </div>
</div>
