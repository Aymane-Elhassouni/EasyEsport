@props([
    'title'  => null,
    'icon'   => null,   
    'badge'  => null,  
    'noPad'  => false,
])

<div {{ $attributes->merge(['class' => 'glass rounded-3xl relative overflow-hidden group transition-all duration-300 hover:shadow-premium']) }}>

    {{-- Glow decoration --}}
    <div class="absolute -right-12 -top-12 w-32 h-32 bg-primary/5 blur-3xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-0"></div>

    @if($title)
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-white/5">
            <div class="flex items-center gap-3">
                @if($icon)
                    <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            {!! $icon !!}
                        </svg>
                    </div>
                @endif
                <h3 class="font-display font-bold text-base">{{ $title }}</h3>
            </div>

            <div class="flex items-center gap-3">
                @if($badge)
                    <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-widest bg-primary/10 text-primary rounded-lg border border-primary/20">
                        {{ $badge }}
                    </span>
                @endif
                @isset($action)
                    {{ $action }}
                @endisset
            </div>
        </div>
    @endif

    <div class="{{ $noPad ? '' : 'p-6' }} relative z-10">
        {{ $slot }}
    </div>
</div>
