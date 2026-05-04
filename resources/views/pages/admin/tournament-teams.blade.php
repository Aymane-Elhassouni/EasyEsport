<x-layouts.app title="Tournament Teams">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-display font-black uppercase tracking-tight">
                {{ $tournament->name }} <span class="text-primary">Teams</span>
            </h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">{{ $tournament->registrations->count() }} approved teams</p>
        </div>
        <div class="flex items-center gap-3">
            @if($tournament->status === 'pending' && $tournament->registrations->count() >= 2)
                <form method="POST" action="{{ route('admin.tournaments.launch', $tournament->id) }}">
                    @csrf
                    <x-ui.button type="submit" variant="primary" size="sm" class="shadow-neon-primary">
                        🚀 Launch Tournament
                    </x-ui.button>
                </form>
            @endif
            <x-ui.button href="{{ route('admin.dashboard') }}" variant="ghost" size="sm">← Back</x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tournament->registrations as $reg)
            <div class="glass p-8 rounded-[2.5rem] border border-white/5 hover:border-primary/30 transition-all duration-500 group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 blur-2xl rounded-full opacity-0 group-hover:opacity-100"></div>
                <div class="flex flex-col items-center text-center space-y-6 relative z-10">
                    <div class="w-24 h-24 bg-dark-bg p-4 rounded-3xl border border-white/10 shadow-2xl group-hover:scale-110 transition-transform">
                        <img src="https://api.dicebear.com/7.x/identicon/svg?seed={{ urlencode($reg->team->name) }}" class="opacity-80">
                    </div>
                    <div>
                        <h4 class="text-lg font-black tracking-tight uppercase leading-none mb-2">{{ $reg->team->name }}</h4>
                        <div class="flex flex-col items-center gap-1">
                             <p class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">Captain: {{ $reg->team->captain?->firstname }} {{ $reg->team->captain?->lastname }}</p>
                             <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">{{ $reg->team->members->count() }} Members</p>
                        </div>
                    </div>
                    
                    <div class="w-full pt-6 border-t border-white/5 flex justify-center gap-4">
                         <div class="text-[10px] font-black uppercase text-secondary">Registered: {{ $reg->registered_at?->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full glass p-20 rounded-[4rem] text-center opacity-40 border border-white/5">
                <p class="font-black uppercase tracking-widest">No approved teams yet</p>
            </div>
        @endforelse
    </div>

</x-layouts.app>
