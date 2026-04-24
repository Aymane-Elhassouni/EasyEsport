@props([
    'variant' => 'primary',  {{-- primary | secondary | danger | ghost --}}
    'size'    => 'md',        {{-- sm | md | lg --}}
    'type'    => 'button',
    'href'    => null,
])

@php
    $variants = [
        'primary'   => 'bg-primary hover:bg-primary-light text-white shadow-neon-primary hover:shadow-neon-primary/70',
        'secondary' => 'bg-secondary/10 hover:bg-secondary/20 text-secondary border border-secondary/30',
        'danger'    => 'bg-danger/10 hover:bg-danger/20 text-danger border border-danger/30',
        'ghost'     => 'bg-transparent hover:bg-white/5 text-gray-400 hover:text-white border border-white/10',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-7 py-3.5 text-base',
    ];

    $base = 'inline-flex items-center justify-center gap-2 font-bold uppercase tracking-widest rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary/50 disabled:opacity-50 disabled:cursor-not-allowed';
    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
