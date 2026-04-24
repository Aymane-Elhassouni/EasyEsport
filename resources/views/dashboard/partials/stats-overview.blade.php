<!-- Top Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <x-card-premium title="Global Rank" icon="trophy" subtitle="Top 5% Players">
        <div class="mt-2">
            <span class="text-4xl font-display font-black text-primary">#1,248</span>
            <div class="mt-2 flex items-center gap-2 text-xs font-bold text-success">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                <span>+12 positions today</span>
            </div>
        </div>
    </x-card-premium>

    <x-card-premium title="Win Rate" icon="stats" subtitle="Last 30 days">
        <div class="mt-2">
            <span class="text-4xl font-display font-black">{{ $winRate ?? 78 }}%</span>
            <div class="mt-4 w-full h-1.5 bg-gray-200 dark:bg-white/10 rounded-full overflow-hidden">
                <div class="h-full bg-primary rounded-full shadow-neon-primary transition-all duration-1000" style="width: {{ $winRate ?? 78 }}%"></div>
            </div>
        </div>
    </x-card-premium>

    <x-card-premium title="Total Earnings" icon="trophy" subtitle="Tournament prizes">
        <div class="mt-2">
            <span class="text-4xl font-display font-black">$4,250</span>
            <p class="text-[10px] mt-1 opacity-50 uppercase tracking-widest">Withdrawal available</p>
        </div>
    </x-card-premium>

    <x-card-premium title="Active Teams" icon="users" subtitle="Ready for matches">
        <div class="mt-2 flex -space-x-3 overflow-hidden">
            @foreach(range(1, 4) as $i)
                <img class="inline-block h-10 w-10 rounded-xl ring-4 ring-white dark:ring-dark-card hover:translate-y-[-4px] transition-transform cursor-pointer" 
                     src="https://api.dicebear.com/7.x/identicon/svg?seed=team{{ $i }}" alt="team">
            @endforeach
            <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-primary/20 text-primary text-xs font-bold ring-4 ring-white dark:ring-dark-card">+2</div>
        </div>
    </x-card-premium>
</div>
