<x-layouts.app title="Admin Dashboard">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-display font-black uppercase tracking-tight">
                Admin <span class="text-primary">Overview</span>
            </h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">Platform control center</p>
        </div>
        <x-ui.button href="{{ route('settings') }}" variant="secondary" size="sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Settings
        </x-ui.button>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Matches --}}
        <div class="lg:col-span-2">
            @include('pages.admin.partials.recent-matches')
        </div>

        {{-- Quick Actions --}}
        <div class="space-y-5">
            <x-ui.card title="Quick Actions" icon='<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>'>
                <div class="space-y-3">
                    @foreach([
                        ['label' => 'Manage Tournaments', 'route' => 'tournaments',        'variant' => 'primary'],
                        ['label' => 'Manage Teams',       'route' => 'admin.teams',        'variant' => 'secondary'],
                        ['label' => 'Registrations',      'route' => 'admin.registrations','variant' => 'ghost'],
                        ['label' => 'Manage Disputes',    'route' => 'admin.disputes',     'variant' => 'ghost'],
                        ['label' => 'OCR Monitor',        'route' => 'admin.ocr',          'variant' => 'ghost'],
                    ] as $action)
                        <x-ui.button href="{{ route($action['route']) }}"
                                     variant="{{ $action['variant'] }}"
                                     class="w-full justify-center">
                            {{ $action['label'] }}
                        </x-ui.button>
                    @endforeach
                </div>
            </x-ui.card>

            @include('pages.admin.partials.my-tournaments')
        </div>

    </div>

</x-layouts.app>
