<x-layouts.app>
    <x-slot name="title">Settings</x-slot>

    <div class="max-w-5xl mx-auto space-y-8">
        @php $user = auth()->user(); @endphp

        @if($user->hasRole('admin') || $user->hasRole('super_admin'))
            <div class="glass border border-white/10 rounded-3xl p-8">
                <h2 class="text-xl font-display font-bold mb-6">Account Settings</h2>
                <form method="POST" action="{{ route('settings.account') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest opacity-60 mb-2">First Name</label>
                            <input type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}"
                                   class="w-full bg-dark-surface border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest opacity-60 mb-2">Last Name</label>
                            <input type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}"
                                   class="w-full bg-dark-surface border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all">
                        </div>
                    </div>
                    <div x-data="{ dragging: false, preview: null }"
                         @dragover.prevent="dragging = true"
                         @dragleave.prevent="dragging = false"
                         @drop.prevent="dragging = false; const f = $event.dataTransfer.files[0]; if(f){ if(f.size > 2097152){ Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Image must be under 2MB', showConfirmButton: false, timer: 3000, background: '#1e293b', color: '#f1f5f9', iconColor: '#ef4444' }); return; } const dt = new DataTransfer(); dt.items.add(f); $refs.input.files = dt.files; const r = new FileReader(); r.onload = e => preview = e.target.result; r.readAsDataURL(f); }">
                        <label class="block text-xs font-bold uppercase tracking-widest opacity-60 mb-2">Profile Picture</label>
                        <div @click="$refs.input.click()"
                             :class="dragging ? 'border-primary/70 bg-primary/10' : 'border-white/20 bg-dark-surface hover:border-primary/50 hover:bg-primary/5'"
                             class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed rounded-2xl cursor-pointer transition-all">
                            <template x-if="preview">
                                <div class="flex flex-col items-center gap-2">
                                    <img :src="preview" class="w-16 h-16 rounded-xl object-cover ring-2 ring-primary/40">
                                    <span class="text-[10px] text-primary font-semibold">Image selected</span>
                                    <button type="button" @click.stop="preview = null; $refs.input.value = ''"
                                            class="text-[10px] text-danger font-bold hover:underline">Remove</button>
                                </div>
                            </template>
                            <template x-if="!preview">
                                <div class="flex flex-col items-center">
                                    <svg class="w-8 h-8 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    <span class="text-xs text-gray-500 font-semibold">Drop image here or <span class="text-primary">browse</span></span>
                                    <span class="text-[10px] text-gray-600 mt-1">JPG, PNG, WEBP — max 2MB</span>
                                </div>
                            </template>
                        </div>
                        <input x-ref="input" type="file" name="logo" accept="image/*" class="hidden"
                               @change="const f = $refs.input.files[0]; if(f){ if(f.size > 2097152){ Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Image must be under 2MB', showConfirmButton: false, timer: 3000, background: '#1e293b', color: '#f1f5f9', iconColor: '#ef4444' }); $refs.input.value=''; return; } const r = new FileReader(); r.onload = e => preview = e.target.result; r.readAsDataURL(f); }">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:shadow-neon-primary transition-all duration-300">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- Email & Password --}}
            <div class="glass border border-white/10 rounded-3xl p-8">
                <h2 class="text-xl font-display font-bold mb-6">Security</h2>

                {{-- Email --}}
                <form method="POST" action="{{ route('settings.account') }}" class="space-y-4 mb-8 pb-8 border-b border-white/10">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="firstname" value="{{ $user->firstname }}">
                    <input type="hidden" name="lastname" value="{{ $user->lastname }}">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest opacity-60 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full bg-dark-surface border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:shadow-neon-primary transition-all duration-300">Update Email</button>
                    </div>
                </form>

                {{-- Password --}}
                <form method="POST" action="{{ route('settings.password') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest opacity-60 mb-2">Current Password</label>
                        <input type="password" name="current_password"
                               class="w-full bg-dark-surface border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest opacity-60 mb-2">New Password</label>
                            <input type="password" name="password"
                                   class="w-full bg-dark-surface border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest opacity-60 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full bg-dark-surface border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:shadow-neon-primary transition-all duration-300">Update Password</button>
                    </div>
                </form>
            </div>
        @else
            <x-edit-profile-form :data="$data" />

            {{-- Account --}}
            <div class="glass border border-white/10 rounded-3xl p-8">
                <h2 class="text-xl font-display font-bold mb-6">Account</h2>
                <form method="POST" action="{{ route('settings.account') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest opacity-60 mb-2">First Name</label>
                            <input type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}"
                                   class="w-full bg-dark-surface border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest opacity-60 mb-2">Last Name</label>
                            <input type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}"
                                   class="w-full bg-dark-surface border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all">
                        </div>
                    </div>
                    <div x-data="{ dragging: false, preview: null }"
                         @dragover.prevent="dragging = true"
                         @dragleave.prevent="dragging = false"
                         @drop.prevent="dragging = false; const f = $event.dataTransfer.files[0]; if(f){ if(f.size > 2097152){ Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Image must be under 2MB', showConfirmButton: false, timer: 3000, background: '#1e293b', color: '#f1f5f9', iconColor: '#ef4444' }); return; } const dt = new DataTransfer(); dt.items.add(f); $refs.input.files = dt.files; const r = new FileReader(); r.onload = e => preview = e.target.result; r.readAsDataURL(f); }">
                        <label class="block text-xs font-bold uppercase tracking-widest opacity-60 mb-2">Profile Picture</label>
                        <div @click="$refs.input.click()"
                             :class="dragging ? 'border-primary/70 bg-primary/10' : 'border-white/20 bg-dark-surface hover:border-primary/50 hover:bg-primary/5'"
                             class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed rounded-2xl cursor-pointer transition-all">
                            <template x-if="preview">
                                <div class="flex flex-col items-center gap-2">
                                    <img :src="preview" class="w-16 h-16 rounded-xl object-cover ring-2 ring-primary/40">
                                    <span class="text-[10px] text-primary font-semibold">Image selected</span>
                                    <button type="button" @click.stop="preview = null; $refs.input.value = ''"
                                            class="text-[10px] text-danger font-bold hover:underline">Remove</button>
                                </div>
                            </template>
                            <template x-if="!preview">
                                <div class="flex flex-col items-center">
                                    <svg class="w-8 h-8 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    <span class="text-xs text-gray-500 font-semibold">Drop image here or <span class="text-primary">browse</span></span>
                                    <span class="text-[10px] text-gray-600 mt-1">JPG, PNG, WEBP — max 2MB</span>
                                </div>
                            </template>
                        </div>
                        <input x-ref="input" type="file" name="logo" accept="image/*" class="hidden"
                               @change="const f = $refs.input.files[0]; if(f){ if(f.size > 2097152){ Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Image must be under 2MB', showConfirmButton: false, timer: 3000, background: '#1e293b', color: '#f1f5f9', iconColor: '#ef4444' }); $refs.input.value=''; return; } const r = new FileReader(); r.onload = e => preview = e.target.result; r.readAsDataURL(f); }">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:shadow-neon-primary transition-all duration-300">Save Changes</button>
                    </div>
                </form>
            </div>

            {{-- Email & Password --}}
            <div class="glass border border-white/10 rounded-3xl p-8">
                <h2 class="text-xl font-display font-bold mb-6">Security</h2>

                <form method="POST" action="{{ route('settings.account') }}" class="space-y-4 mb-8 pb-8 border-b border-white/10">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="firstname" value="{{ $user->firstname }}">
                    <input type="hidden" name="lastname" value="{{ $user->lastname }}">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest opacity-60 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full bg-dark-surface border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:shadow-neon-primary transition-all duration-300">Update Email</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('settings.password') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest opacity-60 mb-2">Current Password</label>
                        <input type="password" name="current_password"
                               class="w-full bg-dark-surface border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest opacity-60 mb-2">New Password</label>
                            <input type="password" name="password"
                                   class="w-full bg-dark-surface border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest opacity-60 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full bg-dark-surface border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:shadow-neon-primary transition-all duration-300">Update Password</button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</x-layouts.app>
