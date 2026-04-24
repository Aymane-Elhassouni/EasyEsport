@php
    $presenter = new \App\Presenters\TeamPresenter();
    $data = $presenter->present($team);
@endphp

<x-layouts.app>
    <x-slot name="title">Team Management HQ</x-slot>

    <div class="space-y-8 animate-fade-in" x-data="{ activeTab: 'roster' }">
        
        {{-- Inclusion du Header (Identité & Stats) --}}
        @include('pages.teams.partials.header', ['team' => $data])

        {{-- Contenu des Onglets --}}
        <div>
            {{-- Tab Content: Roster --}}
            <div x-show="activeTab === 'roster'" x-transition>
                @include('pages.teams.partials.roster', ['team' => $team])
            </div>

            {{-- Tab Content: Requests --}}
            <div x-show="activeTab === 'requests'" x-transition>
                @include('pages.teams.partials.requests', ['team' => $team])
            </div>

            {{-- Tab Content: Settings --}}
            <div x-show="activeTab === 'settings'" x-transition>
                @include('pages.teams.partials.settings', ['team' => $team])
            </div>
        </div>

    </div>
</x-layouts.app>
