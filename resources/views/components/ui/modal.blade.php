@props([
    'id'    => 'modal',
    'title' => 'Modal',
    'size'  => 'md',   {{-- sm | md | lg | xl --}}
])

@php
    $widths = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
    ];
    $width = $widths[$size] ?? $widths['md'];
@endphp

{{--
    Usage:
    <x-ui.modal id="confirm-modal" title="Confirm Action">
        <x-slot:trigger>
            <x-ui.button>Open</x-ui.button>
        </x-slot:trigger>
        Modal body content here.
        <x-slot:footer>
            <x-ui.button variant="danger">Confirm</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
--}}

<div x-data="{ open: false }" id="{{ $id }}">

    {{-- Trigger slot --}}
    @isset($trigger)
        <div @click="open = true">{{ $trigger }}</div>
    @endisset

    {{-- Backdrop + Dialog --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-cloak>

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="open = false"></div>

        {{-- Panel --}}
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative w-full {{ $width }} glass rounded-3xl border border-white/10 shadow-glass z-10">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-white/5">
                <h2 class="font-display font-bold text-lg">{{ $title }}</h2>
                <button @click="open = false"
                        class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            @isset($footer)
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-white/5">
                    <x-ui.button variant="ghost" @click="open = false">Cancel</x-ui.button>
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
