<x-layouts.app>
    <x-slot name="title">Notifications</x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-display font-black tracking-tight uppercase">Notifications</h1>
            @if($notifications->isNotEmpty())
                <form action="{{ route('notifications.clear') }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="px-4 py-2 bg-danger/10 text-danger border border-danger/20 rounded-xl text-xs font-bold hover:bg-danger hover:text-white transition-all">
                        CLEAR ALL
                    </button>
                </form>
            @endif
        </div>

        @forelse($notifications as $notif)
            <div class="glass p-6 rounded-3xl border {{ $notif->is_read ? 'border-white/5' : 'border-primary/30' }} flex items-start gap-5 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-xl flex-shrink-0">
                    {{ $notif->icon ?? '🔔' }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between gap-4">
                        <h4 class="font-black text-sm uppercase tracking-widest">{{ $notif->title }}</h4>
                        <span class="text-[10px] opacity-40">{{ $notif->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm opacity-60 mt-1">{{ $notif->message }}</p>
                    @if($notif->action_url)
                        <a href="{{ $notif->action_url }}" class="inline-block mt-3 text-xs font-bold text-primary hover:underline">View →</a>
                    @endif
                </div>
                <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST" class="flex-shrink-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-2 text-white/20 hover:text-danger transition-colors rounded-lg hover:bg-danger/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </form>
            </div>
        @empty
            <div class="glass p-12 rounded-3xl border border-white/5 text-center opacity-40">
                <p class="text-sm font-medium uppercase tracking-widest">No notifications</p>
            </div>
        @endforelse
    </div>
</x-layouts.app>
