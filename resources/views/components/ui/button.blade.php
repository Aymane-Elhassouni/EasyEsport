@props([
    'variant' => 'primary',
    'size'    => 'md',
    'type'    => 'button',
    'href'    => null,
])

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' =>
        'inline-flex items-center justify-center gap-2 font-bold uppercase tracking-widest rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary/50 disabled:opacity-50 disabled:cursor-not-allowed '
        . match($variant) {
            'secondary' => 'bg-secondary/10 hover:bg-secondary/20 text-secondary border border-secondary/30',
            'danger'    => 'bg-danger/10 hover:bg-danger/20 text-danger border border-danger/30',
            'ghost'     => 'bg-transparent hover:bg-white/5 text-gray-400 hover:text-white border border-white/10',
            default     => 'bg-primary hover:bg-primary-light text-white shadow-neon-primary hover:shadow-neon-primary/70',
        }
        . ' ' . match($size) {
            'sm'    => 'px-3 py-1.5 text-xs',
            'lg'    => 'px-7 py-3.5 text-base',
            default => 'px-5 py-2.5 text-sm',
        }
    ]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' =>
        'inline-flex items-center justify-center gap-2 font-bold uppercase tracking-widest rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary/50 disabled:opacity-50 disabled:cursor-not-allowed '
        . match($variant) {
            'secondary' => 'bg-secondary/10 hover:bg-secondary/20 text-secondary border border-secondary/30',
            'danger'    => 'bg-danger/10 hover:bg-danger/20 text-danger border border-danger/30',
            'ghost'     => 'bg-transparent hover:bg-white/5 text-gray-400 hover:text-white border border-white/10',
            default     => 'bg-primary hover:bg-primary-light text-white shadow-neon-primary hover:shadow-neon-primary/70',
        }
        . ' ' . match($size) {
            'sm'    => 'px-3 py-1.5 text-xs',
            'lg'    => 'px-7 py-3.5 text-base',
            default => 'px-5 py-2.5 text-sm',
        }
    ]) }}>
        {{ $slot }}
    </button>
@endif
