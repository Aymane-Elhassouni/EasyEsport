@props(['data' => null])

@php
    $user = Auth::user();
    // Precise data from DTO and Models
    $displayName = $data->displayName ?? trim($user->firstname . ' ' . $user->lastname);
    $email = $user->email;
    $bio = $user->profile->bio ?? 'No bio provided.';
    $matches = $data->matches ?? ($user->profile->total_matches ?? 0);
    $wins = $data->wins ?? 0;
    $winRate = $data->winRate ?? ($user->profile->win_rate ?? 0);
    $trophies = $data->trophies ?? ($user->profile->total_trophies ?? 0);
    $rank = $data->rankName ?? 'Unranked';
    $avatar = $user->avatar_url;
@endphp

<x-card title="Profile Information" class="w-full" x-data="{ 
    isEditing: false, 
    form: {
        bio: '{{ addslashes($bio) }}',
        total_matches: {{ $matches }},
        total_trophies: {{ $trophies }}
    },
    async save() {
        try {
            const response = await fetch('{{ route('profile.update') }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.form)
            });

            if (response.ok) {
                $store.global.notify('Profile updated successfully!', 'success');
                this.isEditing = false;
                // In a real app, we might reload or update state. 
                // For this simulation/UI polish, we'll just close edit mode.
            } else {
                const err = await response.json();
                $store.global.notify(err.message || 'Update failed', 'danger');
            }
        } catch (e) {
            $store.global.notify('Connection error', 'danger');
        }
    }
}">
    <x-slot name="icon">
        <svg fill="none" class="w-5 h-5" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
    </x-slot>
    
    <x-slot name="action">
        <button @click="isEditing = !isEditing" class="btn-secondary py-1.5 px-4 text-xs transition-all">
            <span x-show="!isEditing">Edit Profile</span>
            <span x-show="isEditing" x-cloak class="text-danger flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Cancel
            </span>
        </button>
    </x-slot>

    <!-- Display Mode -->
    <div x-show="!isEditing" x-transition.opacity.duration.300ms class="flex flex-col md:flex-row gap-8 items-start">
        <div class="relative group">
            <div class="w-32 h-32 rounded-2xl overflow-hidden ring-4 ring-white/5 bg-dark-bg border border-white/5 flex items-center justify-center p-1">
                <img src="{{ $avatar }}" class="w-full h-full object-cover rounded-xl opacity-90 transition-opacity group-hover:opacity-100">
            </div>
            <div class="absolute -bottom-3 -right-3 bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-neon-primary text-xs font-bold border-2 border-dark-bg">
                <span>{{ $trophies }}</span>
            </div>
        </div>

        <div class="flex-1 w-full space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold opacity-50 uppercase tracking-widest text-primary">Player Info</p>
                    <p class="font-bold text-2xl font-display">{{ $displayName }}</p>
                    <p class="text-xs text-gray-500 font-medium">Tournament Player</p>
                </div>
                
                <div class="space-y-1">
                    <p class="text-[10px] font-bold opacity-50 uppercase tracking-widest">Email Identity</p>
                    <p class="font-bold text-sm tracking-wide">{{ $email }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-dark-surface dark:bg-white/5 p-4 rounded-xl border border-white/5">
                    <p class="text-[10px] font-bold opacity-50 uppercase tracking-widest mb-1">Career Matches</p>
                    <p class="font-black text-2xl font-display">{{ $matches }}</p>
                </div>
                <div class="bg-dark-surface dark:bg-white/5 p-4 rounded-xl border border-white/5">
                    <p class="text-[10px] font-bold opacity-50 uppercase tracking-widest mb-1">Wins</p>
                    <p class="font-black text-2xl text-success font-display">{{ $wins }}</p>
                </div>
                <div class="bg-dark-surface dark:bg-white/5 p-4 rounded-xl border border-white/5">
                    <p class="text-[10px] font-bold opacity-50 uppercase tracking-widest mb-1">Success Rate</p>
                    <p class="font-black text-2xl text-primary font-display">{{ $winRate }}%</p>
                </div>
                <div class="bg-dark-surface dark:bg-white/5 p-4 rounded-xl border border-white/5">
                    <p class="text-[10px] font-bold opacity-50 uppercase tracking-widest mb-1">Global Rank</p>
                    <p class="font-black text-sm mt-2 uppercase tracking-wide">{{ $rank }}</p>
                </div>
            </div>

            <div class="bg-primary/5 border border-primary/20 rounded-xl p-4">
                <p class="text-[10px] font-bold text-primary uppercase tracking-widest mb-2">Biography</p>
                <div class="text-xs font-medium opacity-80 leading-relaxed max-w-2xl">
                    {{ $bio }}
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Mode -->
    <div x-show="isEditing" x-cloak x-transition.opacity.duration.300ms class="space-y-6">
        <form @submit.prevent="save" class="space-y-8 max-w-3xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Matches -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold uppercase tracking-widest opacity-60">Total Matches (Simulation)</label>
                    <input type="number" x-model="form.total_matches"
                           class="w-full bg-dark-bg dark:bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all">
                </div>

                <!-- Trophies -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold uppercase tracking-widest opacity-60">Total Trophies</label>
                    <input type="number" x-model="form.total_trophies"
                           class="w-full bg-dark-bg dark:bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all">
                </div>
            </div>

            <!-- Bio -->
            <div class="space-y-2">
                <label class="block text-[10px] font-bold uppercase tracking-widest opacity-60">Personal Biography</label>
                <textarea x-model="form.bio" rows="4"
                          class="w-full bg-dark-bg dark:bg-white/5 border border-white/10 rounded-xl px-4 py-4 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all resize-none"></textarea>
            </div>

            <div class="flex justify-end gap-4 border-t border-white/5 pt-6">
                <button type="button" @click="isEditing = false" class="px-6 py-3 text-xs font-bold opacity-60 hover:opacity-100 transition-opacity">Discard</button>
                <button type="submit" class="btn-primary py-3 px-10 shadow-neon-primary text-xs uppercase font-black tracking-widest">
                    Apply Updates
                </button>
            </div>
        </form>
    </div>
</x-card>
