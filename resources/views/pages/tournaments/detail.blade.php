<x-layouts.app>
    <x-slot name="title">{{ $tournament->name }} HQ</x-slot>

    <div class="space-y-8 animate-fade-in" x-data="{ activeTab: 'announcements' }">
        
        {{-- Inclusion du Header (Identité & Registration) --}}
        @include('pages.tournaments.partials.header', ['tournament' => $tournament, 'data' => $tournament, 'userTeam' => $userTeam])

        {{-- Contenu des Onglets --}}
        <div>
            {{-- Tab Content: Announcements --}}
            <div x-show="activeTab === 'announcements'" x-transition>
                @include('pages.tournaments.partials.announcements', ['tournament' => $tournament])
            </div>

            {{-- Tab Content: Brackets --}}
            <div x-show="activeTab === 'brackets'" x-transition>
                @include('pages.tournaments.partials.brackets', ['tournament' => $tournament])
            </div>

            {{-- Tab Content: Standings --}}
            @if($tournament->has_group_stage)
            <div x-show="activeTab === 'standings'" x-transition>
                @include('pages.tournaments.partials.standings', ['tournament' => $tournament])
            </div>
            @endif

            {{-- Tab Content: Teams --}}
            <div x-show="activeTab === 'teams'" x-transition>
                @include('pages.tournaments.partials.teams', ['tournament' => $tournament])
            </div>

            @if(auth()->user()?->hasRole('admin') || auth()->user()?->hasRole('super_admin'))
            {{-- Tab Content: Registrations (admin only) --}}
            <div x-show="activeTab === 'registrations'" x-transition>
                @include('pages.tournaments.partials.registrations', ['tournament' => $tournament])
            </div>
            @endif

            {{-- Tab Content: Rules --}}
            <div x-show="activeTab === 'rules'" x-transition class="max-w-3xl mx-auto space-y-6">
                @foreach([
                    'Format' => $tournament->format_label . ', Best of 3 (Finals Bo5).',
                    'Fair Play' => 'Anti-cheat mandatory. OCR validation required for all matches.',
                    'Scheduling' => 'All matches must be played within 48h of round opening.',
                    'Disputes' => 'Arbitrators decision is final. OCR screenshots must be unedited.'
                ] as $title => $content)
                    <div class="glass p-8 rounded-[2rem] border border-white/5 space-y-3">
                        <h3 class="font-black text-primary uppercase tracking-widest text-xs">{{ $title }}</h3>
                        <p class="text-sm opacity-60 leading-relaxed font-medium">{{ $content }}</p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</x-layouts.app>
