<x-ui.card title="My Tournaments" icon='<path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />'>
    <div class="space-y-4">
        @forelse($my_tournaments as $tournament)
            <div class="flex items-center justify-between p-4 bg-white/2 rounded-2xl border border-white/5 hover:border-primary/20 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-dark-bg border border-white/10 rounded-xl flex items-center justify-center shrink-0">
                         <span class="text-xs font-black text-primary">{{ substr($tournament->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-black uppercase tracking-tight truncate max-w-[150px]">{{ $tournament->name }}</p>
                        <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">{{ $tournament->registrations_count }} Approved Teams</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <x-ui.button href="{{ route('admin.tournaments.teams', $tournament->slug) }}" variant="ghost" size="sm" class="px-3">
                        Teams
                    </x-ui.button>
                    <x-ui.button href="{{ route('tournaments.show', $tournament->slug) }}" variant="ghost" size="sm" class="p-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </x-ui.button>
                </div>
            </div>
        @empty
            <div class="text-center py-8 opacity-40">
                <p class="text-xs font-bold uppercase tracking-widest">No tournaments created</p>
                <x-ui.button href="{{ route('admin.tournaments.create') }}" variant="primary" size="xs" class="mt-4">Create One</x-ui.button>
            </div>
        @endforelse
    </div>
</x-ui.card>
