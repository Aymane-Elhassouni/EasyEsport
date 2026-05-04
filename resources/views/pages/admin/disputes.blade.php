<x-layouts.app title="Pending Disputes">
    <div class="space-y-8">
        <div>
            <h1 class="text-3xl font-display font-black uppercase tracking-tight">Pending <span class="text-primary">Disputes</span></h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">{{ $disputes->count() }} matches requiring intervention</p>
        </div>

        <div class="grid grid-cols-1 gap-6">
            @forelse($disputes as $match)
                <div class="glass rounded-3xl border border-white/5 overflow-hidden hover:border-white/10 transition-all">
                    <div class="bg-white/5 px-8 py-4 flex items-center justify-between border-b border-white/5">
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-black uppercase tracking-widest text-primary">{{ $match->bracket->tournament->name }}</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-white/20"></span>
                            <span class="text-xs font-bold text-gray-400 uppercase">Match #{{ $match->id }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-lg bg-danger/10 text-danger text-[10px] font-black uppercase tracking-widest border border-danger/20">OCR Confidence: {{ $match->ocr_confidence }}%</span>
                        </div>
                    </div>

                    <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {{-- Team A --}}
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="font-black uppercase tracking-tight text-sm">Team A: <span class="text-primary">{{ $match->teamA->name }}</span></h4>
                                <span class="text-xl font-black">{{ $match->score_a }}</span>
                            </div>
                            <div class="aspect-video glass rounded-2xl border border-white/10 overflow-hidden relative group">
                                @if($match->team_a_screenshot)
                                    <img src="{{ Storage::url($match->team_a_screenshot) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <a href="{{ Storage::url($match->team_a_screenshot) }}" target="_blank" class="px-4 py-2 bg-white text-black rounded-xl text-xs font-bold uppercase">View Full Screen</a>
                                    </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500 italic text-xs uppercase font-bold opacity-30">No Screenshot</div>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('admin.disputes.settle', $match->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="winner_id" value="{{ $match->team_a_id }}">
                                <x-ui.button type="submit" variant="primary" class="w-full" size="sm">Force Win for Team A</x-ui.button>
                            </form>
                        </div>

                        {{-- Team B --}}
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="font-black uppercase tracking-tight text-sm">Team B: <span class="text-secondary">{{ $match->teamB->name }}</span></h4>
                                <span class="text-xl font-black">{{ $match->score_b }}</span>
                            </div>
                            <div class="aspect-video glass rounded-2xl border border-white/10 overflow-hidden relative group">
                                @if($match->team_b_screenshot)
                                    <img src="{{ Storage::url($match->team_b_screenshot) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <a href="{{ Storage::url($match->team_b_screenshot) }}" target="_blank" class="px-4 py-2 bg-white text-black rounded-xl text-xs font-bold uppercase">View Full Screen</a>
                                    </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500 italic text-xs uppercase font-bold opacity-30">No Screenshot</div>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('admin.disputes.settle', $match->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="winner_id" value="{{ $match->team_b_id }}">
                                <x-ui.button type="submit" variant="secondary" class="w-full" size="sm">Force Win for Team B</x-ui.button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass p-20 rounded-[3rem] border border-white/5 text-center">
                    <div class="w-20 h-20 bg-success/10 rounded-3xl flex items-center justify-center mx-auto mb-6 text-success border border-success/20">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="text-xl font-bold uppercase tracking-tight">All clear!</h3>
                    <p class="text-gray-400 text-sm mt-2 font-medium">No pending disputes at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
