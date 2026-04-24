<x-layouts.app title="Super Admin — Control Center">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <span class="px-3 py-1 rounded-lg bg-rose-500/10 text-rose-400 text-[10px] font-black uppercase tracking-widest border border-rose-500/20">
                    Super Admin
                </span>
            </div>
            <h1 class="text-2xl font-display font-black uppercase tracking-tight">
                System <span class="text-rose-400">Control Center</span>
            </h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">Full platform access</p>
        </div>
        <div class="flex gap-3">
            <x-ui.button href="{{ route('admin.system.logs') }}" variant="danger" size="sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                System Logs
            </x-ui.button>
            <x-ui.button href="{{ route('settings') }}" variant="ghost" size="sm">Settings</x-ui.button>
        </div>
    </div>

    {{-- Stats Cards (exclusive super_admin) --}}
    @include('pages.admin.partials.stats-cards')

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Activity --}}
        <div class="lg:col-span-2">
            @include('pages.admin.partials.recent-matches')
        </div>

        {{-- System Tools --}}
        <div class="space-y-5">
            <x-ui.card title="System Tools" icon='<path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'>
                <div class="space-y-3">
                    @foreach([
                        ['label' => 'System Logs',      'route' => 'admin.system.logs',    'variant' => 'danger'],
                        ['label' => 'OCR Audit',        'route' => 'admin.system.ocr.audit','variant' => 'secondary'],
                        ['label' => 'User Management',  'route' => 'admin.users',           'variant' => 'primary'],
                        ['label' => 'Manage Disputes',  'route' => 'admin.disputes',        'variant' => 'ghost'],
                        ['label' => 'OCR Monitor',      'route' => 'admin.ocr',             'variant' => 'ghost'],
                    ] as $tool)
                        <x-ui.button href="{{ route($tool['route']) }}"
                                     variant="{{ $tool['variant'] }}"
                                     class="w-full justify-center">
                            {{ $tool['label'] }}
                        </x-ui.button>
                    @endforeach
                </div>
            </x-ui.card>

            {{-- Quick Admin Actions --}}
            <x-ui.card title="Quick Actions" icon='<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>'>
                <div class="space-y-3">
                    @foreach([
                        ['label' => 'Tournaments', 'route' => 'tournaments', 'variant' => 'primary'],
                        ['label' => 'Teams',       'route' => 'teams',       'variant' => 'secondary'],
                    ] as $action)
                        <x-ui.button href="{{ route($action['route']) }}"
                                     variant="{{ $action['variant'] }}"
                                     class="w-full justify-center">
                            {{ $action['label'] }}
                        </x-ui.button>
                    @endforeach
                </div>
            </x-ui.card>
        </div>

    </div>

</x-layouts.app>
