<x-layouts.app title="Admin Profile">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-display font-black uppercase tracking-tight">
                Admin <span class="text-primary">Profile</span>
            </h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">System administrator identity</p>
        </div>
        <x-ui.button href="{{ route('settings') }}" variant="ghost" size="sm">Edit Account</x-ui.button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- Left: Avatar + Role Badge --}}
        <div class="lg:col-span-4 space-y-6">
            <x-ui.card>
                <div class="flex flex-col items-center text-center gap-4">
                    <div class="relative">
                        <img src="{{ auth()->user()->avatar_url }}"
                             class="w-28 h-28 rounded-2xl ring-4 ring-primary/30 bg-dark-surface object-cover">
                        <span class="absolute -bottom-2 -right-2 w-8 h-8 bg-primary rounded-xl flex items-center justify-center shadow-neon-primary">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </span>
                    </div>

                    <div>
                        <h2 class="text-xl font-display font-bold">
                            {{ trim((auth()->user()->firstname ?? '') . ' ' . (auth()->user()->lastname ?? '')) ?: 'Administrator' }}
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">{{ auth()->user()->email }}</p>
                    </div>

                    <span class="px-4 py-1.5 rounded-xl bg-primary/10 text-primary text-xs font-black uppercase tracking-widest border border-primary/20">
                        {{ auth()->user()->role?->name ?? 'Admin' }}
                    </span>
                </div>
            </x-ui.card>

            {{-- Security Overview --}}
            <x-ui.card title="Security" icon='<path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>'>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-400">Account Status</dt>
                        <dd class="font-bold text-success">Verified</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-400">2FA</dt>
                        <dd class="font-bold text-warning">Disabled</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-400">Member Since</dt>
                        <dd class="font-bold">{{ auth()->user()->created_at?->format('M Y') ?? '—' }}</dd>
                    </div>
                </dl>
            </x-ui.card>
        </div>

        {{-- Right: Admin-specific fields --}}
        <div class="lg:col-span-8">
            <x-profile.admin-fields :user="auth()->user()" />
        </div>

    </div>

</x-layouts.app>
