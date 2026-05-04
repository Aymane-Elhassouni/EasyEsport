<x-layouts.app title="Registrations">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-display font-black uppercase tracking-tight">
                Team <span class="text-primary">Applications</span>
            </h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">{{ $counts['all'] }} total applications</p>
        </div>
        <x-ui.button href="{{ route('admin.dashboard') }}" variant="ghost" size="sm">← Back</x-ui.button>
    </div>

    <div x-data="{ filter: 'pending' }">

        {{-- Filter Tabs --}}
        <div class="flex items-center gap-2 mb-8 border-b border-white/5 pb-4">
            @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $val => $lbl)
                <button @click="filter = '{{ $val }}'"
                        :class="filter === '{{ $val }}' ? 'bg-primary text-white shadow-neon-primary' : 'text-gray-400 hover:text-white hover:bg-white/5'"
                        class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest transition-all flex items-center gap-2">
                    {{ $lbl }}
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black"
                          :class="filter === '{{ $val }}' ? 'bg-white/20' : 'bg-white/5'">
                        {{ $counts[$val] }}
                    </span>
                </button>
            @endforeach
        </div>

        {{-- Posts --}}
        <div class="space-y-5">
            @forelse($registrations as $reg)
                @with($cfg = $statusConfig[$reg->status] ?? $statusConfig['pending'])
                <div x-show="filter === 'all' || filter === '{{ $reg->status }}'"
                     class="glass rounded-3xl border border-white/5 overflow-hidden hover:border-primary/20 transition-all">

                    {{-- Post Header --}}
                    <div class="flex items-center justify-between px-6 py-4 border-b border-white/5 bg-white/2">
                        <div class="flex items-center gap-3">
                            <span class="text-lg">{{ $cfg['icon'] }}</span>
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-{{ $cfg['color'] }}">{{ $cfg['label'] }}</p>
                                <p class="text-[10px] text-gray-500">{{ $reg->created_at?->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-gray-400">{{ $reg->tournament->name }}</span>
                    </div>

                    {{-- Post Body --}}
                    <div class="p-6 flex items-center justify-between gap-6">
                        <div class="flex items-center gap-5">
                            <img src="{{ $reg->team->logo_url }}" class="w-16 h-16 rounded-2xl border border-white/10 shrink-0">
                            <div class="space-y-1">
                                <h3 class="font-display font-black text-lg uppercase tracking-tight">{{ $reg->team->name }}</h3>
                                <div class="flex items-center gap-4 text-xs text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Captain: <span class="text-white font-bold">{{ $reg->team->captain?->firstname }} {{ $reg->team->captain?->lastname }}</span>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="text-white font-bold">{{ $reg->team->members->count() }}</span> members
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($reg->status === 'pending')
                            <div class="flex items-center gap-3 shrink-0">
                                <form method="POST" action="{{ route('admin.applications.validate', $reg->tournament->slug) }}">
                                    @csrf
                                    <input type="hidden" name="registration_id" value="{{ $reg->id }}">
                                    <input type="hidden" name="status" value="approved">
                                    <button class="px-6 py-3 bg-success/10 text-success border border-success/20 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-success hover:text-white transition-all">
                                        ✓ Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.applications.validate', $reg->tournament->slug) }}">
                                    @csrf
                                    <input type="hidden" name="registration_id" value="{{ $reg->id }}">
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="px-6 py-3 bg-danger/10 text-danger border border-danger/20 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-danger hover:text-white transition-all">
                                        ✕ Reject
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
                @endwith
            @empty
                <div class="glass p-16 rounded-3xl border border-white/5 text-center opacity-40">
                    <p class="text-sm font-bold uppercase tracking-widest">No applications yet</p>
                </div>
            @endforelse
        </div>
    </div>

</x-layouts.app>
