@props(['title', 'description', 'icon' => 'search', 'actionLabel' => null, 'actionRoute' => '#'])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center text-center p-12 glass rounded-3xl border border-white/10 hover:border-white/20 transition-all group']) }}>
    
    <div class="w-20 h-20 bg-primary/10 rounded-3xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
        @if($icon == 'search')
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        @elseif($icon == 'users')
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        @elseif($icon == 'swords')
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        @endif
    </div>

    <h3 class="text-xl font-display font-black tracking-tight mb-2">{{ $title }}</h3>
    <p class="text-sm opacity-50 max-w-xs mb-8">{{ $description }}</p>

    @if($actionLabel)
        <a href="{{ $actionRoute }}" class="btn-primary shadow-neon-primary px-8 py-3 text-xs font-bold tracking-widest uppercase">
            {{ $actionLabel }}
        </a>
    @endif
</div>
