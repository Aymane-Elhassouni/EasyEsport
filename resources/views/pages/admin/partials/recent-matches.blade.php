<x-card title="System Activity" icon='<path d="M13 10V3L4 14h7v7l9-11h-7z" />'>
    <div class="space-y-6">
        @forelse($recent_matches as $match)
            <div class="flex items-center justify-between p-4 bg-dark-bg/20 dark:bg-white/5 rounded-2xl border border-white/5 group hover:border-primary/30 transition-all">
                <div class="flex items-center gap-4">
                    <div class="flex -space-x-2">
                        <div class="w-8 h-8 rounded-lg bg-primary/20 border border-primary/30 flex items-center justify-center text-[10px] font-black">A</div>
                        <div class="w-8 h-8 rounded-lg bg-secondary/20 border border-secondary/30 flex items-center justify-center text-[10px] font-black">B</div>
                    </div>
                    <div>
                        <p class="text-sm font-black uppercase tracking-tight">{{ $match->teamA->name ?? 'TBD' }} vs {{ $match->teamB->name ?? 'TBD' }}</p>
                        <p class="text-[10px] opacity-40 font-bold uppercase">{{ $match->status }} • {{ $match->played_at?->diffForHumans() ?? 'In progress' }}</p>
                    </div>
                </div>
                <span class="text-xs font-black text-primary">{{ $match->score_a ?? 0 }} - {{ $match->score_b ?? 0 }}</span>
            </div>
        @empty
            <div class="text-center py-12 opacity-40">
                <p class="text-sm font-bold uppercase tracking-widest">No recent matches</p>
            </div>
        @endforelse

        <div class="pt-4 border-t border-white/5">
            <a href="{{ route('admin.disputes') }}" class="text-xs font-black text-primary hover:underline uppercase tracking-widest flex items-center gap-2">
                View All Matches
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</x-card>
