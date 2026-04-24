@props(['title' => '', 'icon' => null])

<div {{ $attributes->merge(['class' => 'glass rounded-3xl p-6 md:p-8 hover:shadow-premium transition-all duration-300 relative overflow-hidden group']) }}>
    
    @if($title)
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <div class="flex items-center gap-3">
                @if($icon)
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                        {!! $icon !!}
                    </div>
                @endif
                <h3 class="text-xl font-display font-bold">{{ $title }}</h3>
            </div>
            @if(isset($action))
                <div>{{ $action }}</div>
            @endif
        </div>
    @endif

    <div class="relative z-10 w-full h-full">
        {{ $slot }}
    </div>

    <!-- Hover Gradient Decoration -->
    <div class="absolute -right-16 -top-16 w-32 h-32 bg-primary/5 blur-3xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity z-0 pointer-events-none"></div>
</div>
