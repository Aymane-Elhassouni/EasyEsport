<x-layouts.app :title="$tournament->name">

    {{-- Back --}}
    <div class="mb-6">
        <x-ui.button href="{{ route('tournaments') }}" variant="ghost" size="sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Tournaments
        </x-ui.button>
    </div>

    {{-- Hero Banner --}}
    <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-primary/20 via-dark-surface to-secondary/10 border border-white/5 p-8 md:p-12 mb-8">
        <div class="absolute inset-0 bg-grid-white opacity-10 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3">
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="px-3 py-1 rounded-lg bg-primary/10 text-primary text-xs font-black uppercase tracking-widest border border-primary/20">
                        {{ $tournament->game ?? 'eSport' }}
                    </span>
                    @if($tournament->status ?? false)
                        <span class="px-3 py-1 rounded-lg text-xs font-black uppercase tracking-widest
                            {{ $tournament->status === 'active' ? 'bg-success/10 text-success border border-success/20' : 'bg-gray-500/10 text-gray-400 border border-gray-500/20' }}">
                            {{ $tournament->status }}
                        </span>
                    @endif
                </div>
                <h1 class="text-3xl md:text-4xl font-display font-black uppercase tracking-tight">
                    {{ $tournament->name }}
                </h1>
                <p class="text-gray-400 text-sm max-w-xl">{{ $tournament->description ?? 'No description available.' }}</p>
            </div>

            <div class="shrink-0 text-center">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Prize Pool</p>
                <p class="text-4xl font-display font-black text-warning">{{ $tournament->prize_pool ?? 'TBD' }}</p>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: 'brackets' }" class="space-y-6">
        <div class="flex gap-2 border-b border-white/5 pb-1">
            @foreach(['brackets' => 'Brackets', 'teams' => 'Teams', 'rules' => 'Rules'] as $key => $label)
                <button @click="tab = '{{ $key }}'"
                        :class="tab === '{{ $key }}' ? 'text-primary border-b-2 border-primary' : 'text-gray-400 hover:text-white'"
                        class="px-4 py-2 text-xs font-black uppercase tracking-widest transition-colors -mb-px">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div x-show="tab === 'brackets'" x-transition>
            @include('pages.tournaments.partials.brackets', ['tournament' => $tournament])
        </div>

        <div x-show="tab === 'teams'" x-transition>
            @include('pages.tournaments.partials.teams', ['tournament' => $tournament])
        </div>

        <div x-show="tab === 'rules'" x-transition>
            <div class="max-w-3xl space-y-4">
                @foreach([
                    'Format'     => 'Single elimination, Best of 3 (Finals Bo5).',
                    'Fair Play'  => 'Anti-cheat mandatory. OCR validation required for all matches.',
                    'Scheduling' => 'All matches must be played within 48h of round opening.',
                    'Disputes'   => 'Arbitrator\'s decision is final. OCR screenshots must be unedited.',
                ] as $title => $content)
                    <x-ui.card>
                        <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-2">{{ $title }}</h3>
                        <p class="text-sm text-gray-400 leading-relaxed">{{ $content }}</p>
                    </x-ui.card>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Admin-only management panel --}}
    @if(auth()->user()->role?->name === 'admin' || auth()->user()->role?->name === 'super_admin')
        <div class="mt-8 pt-8 border-t border-white/5">
            <x-ui.card title="Admin Controls"
                       icon='<path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'>
                <div class="flex flex-wrap gap-3">
                    <x-ui.button variant="secondary" size="sm">Edit Tournament</x-ui.button>
                    <x-ui.button variant="ghost" size="sm">Manage Brackets</x-ui.button>
                    <x-ui.button variant="danger" size="sm">Cancel Tournament</x-ui.button>
                </div>
            </x-ui.card>
        </div>
    @endif

</x-layouts.app>
