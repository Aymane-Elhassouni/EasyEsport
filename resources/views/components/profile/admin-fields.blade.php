{{--
    Admin-specific profile fields.
    Props: $user (App\Models\User)
--}}
@props(['user'])

<div class="space-y-6">

    {{-- Section: Identity --}}
    <x-ui.card title="Admin Identity"
               icon='<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>'>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">First Name</label>
                <p class="text-sm font-semibold">{{ $user->firstname ?? '—' }}</p>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Last Name</label>
                <p class="text-sm font-semibold">{{ $user->lastname ?? '—' }}</p>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Email</label>
                <p class="text-sm font-semibold">{{ $user->email }}</p>
            </div>
        </div>
    </x-ui.card>

    {{-- Section: Permissions --}}
    <x-ui.card title="Assigned Permissions"
               icon='<path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>'>
        @if($user->role && $user->role->permissions->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($user->role->permissions as $permission)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5 hover:border-primary/30 transition-colors">
                        <div class="w-7 h-7 rounded-lg bg-primary/10 flex items-center justify-center text-primary shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold">{{ $permission->display_name ?? $permission->name }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-gray-500">No permissions assigned.</p>
        @endif
    </x-ui.card>

    {{-- Section: Admin Actions --}}
    <x-ui.card title="Quick Admin Actions"
               icon='<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>'>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-ui.button href="{{ route('admin.dashboard') }}" variant="secondary" class="w-full justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Admin Dashboard
            </x-ui.button>
            <x-ui.button href="{{ route('settings') }}" variant="ghost" class="w-full justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Settings
            </x-ui.button>
        </div>
    </x-ui.card>

</div>
