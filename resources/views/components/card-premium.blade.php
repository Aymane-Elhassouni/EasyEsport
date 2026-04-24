@props(['title' => null, 'icon' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'card-premium group']) }}>
    @if($title || $icon)
    <div class="flex items-start justify-between mb-6">
        <div>
            @if($title)
                <h3 class="text-lg font-bold tracking-tight">{{ $title }}</h3>
            @endif
            @if($subtitle)
                <p class="text-xs opacity-50">{{ $subtitle }}</p>
            @endif
        </div>
        @if($icon)
            <div class="p-2.5 rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($icon == 'stats')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    @elseif($icon == 'users')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    @elseif($icon == 'trophy')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    @endif
                </svg>
            </div>
        @endif
    </div>
    @endif

    {{ $slot }}
    
    <!-- Decorative Glow -->
    <div class="absolute -right-12 -bottom-12 w-24 h-24 bg-primary/5 blur-3xl rounded-full group-hover:bg-primary/10 transition-all duration-700"></div>
</div>
