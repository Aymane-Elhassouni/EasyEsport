<x-layouts.app title="My Profile">

    @php $user = auth()->user(); @endphp

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-display font-black uppercase tracking-tight">
                My <span class="text-primary">Profile</span>
            </h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">Player Identity</p>
        </div>
        <x-ui.button href="{{ route('settings') }}" variant="ghost" size="sm">Edit Profile</x-ui.button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- Left: Shared Avatar Card --}}
        <div class="lg:col-span-4 space-y-6">
            <x-ui.card>
                <div class="flex flex-col items-center text-center gap-4">
                    <div class="relative">
                        <img src="{{ $user->avatar_url }}"
                             class="w-28 h-28 rounded-2xl ring-4 ring-primary/30 bg-dark-surface object-cover">

                        <span class="absolute -bottom-2 -right-2 w-8 h-8 bg-secondary rounded-xl flex items-center justify-center shadow-neon-secondary">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                            </svg>
                        </span>
                    </div>

                    <div>
                        <h2 class="text-xl font-display font-bold">
                            {{ trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?: ($user->name ?? 'User') }}
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</p>
                    </div>

                    <span class="px-4 py-1.5 rounded-xl bg-secondary/10 text-secondary border border-secondary/20 text-xs font-black uppercase tracking-widest">
                        {{ $user->role?->name ?? 'Player' }}
                    </span>
                </div>
            </x-ui.card>

            {{-- Shared account info --}}
            <x-ui.card title="Account Info" icon='<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-400">Status</dt>
                        <dd class="font-bold text-success">Active</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-400">Member Since</dt>
                        <dd class="font-bold">{{ $user->created_at?->format('M Y') ?? '—' }}</dd>
                    </div>
                </dl>
            </x-ui.card>
        </div>

        {{-- Right: Player-specific fields --}}
        <div class="lg:col-span-8">
            <x-profile.player-fields :user="$user" />
        </div>

    </div>

</x-layouts.app>
