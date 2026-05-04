<div class="max-w-2xl mx-auto glass p-10 rounded-[3rem] border border-white/5">
    <form action="{{ route('teams.update', $team->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf @method('PATCH')
        
        <div class="space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-[0.2em] opacity-40">Identity</h3>
            <div class="space-y-4">
                <div class="space-y-1.5 px-1">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Team Name</label>
                    <input type="text" name="name" value="{{ $team->name }}" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-medium focus:outline-none focus:border-primary transition-all">
                </div>
                <div class="space-y-1.5 px-1">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Team Logo</label>
                    <div class="flex items-center gap-6" x-data="{ preview: '{{ $team->logo_url }}' }">
                        <img :src="preview" class="w-16 h-16 rounded-2xl object-cover border border-white/10" alt="logo">
                        <label class="cursor-pointer flex items-center gap-3 px-6 py-3 bg-white/5 border border-white/10 rounded-2xl hover:border-primary transition-all">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-widest">Choose Image</span>
                            <input type="file" name="logo" class="hidden" accept="image/*"
                                @change="preview = URL.createObjectURL($event.target.files[0])">
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-[0.2em] opacity-40">Privacy & Recruitment</h3>
            <div class="space-y-4">
                <label class="flex items-center justify-between p-4 glass rounded-2xl border border-white/5 cursor-pointer group">
                    <div>
                        <p class="text-sm font-bold">Public Recruiting</p>
                        <p class="text-xs opacity-50">Show team in recruitment list</p>
                    </div>
                    <input type="checkbox" checked class="w-6 h-6 rounded-lg bg-dark-bg border-white/10 text-primary focus:ring-primary shadow-neon-primary">
                </label>
            </div>
        </div>

        <button class="w-full btn-primary py-4 text-sm font-bold shadow-neon-primary tracking-[0.2em] transform hover:scale-105 transition-all">SAVE HQ CONFIGURATION</button>
    </form>

    <div class="mt-12 pt-12 border-t border-white/5 space-y-8">
        <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-warning mb-4">Transfer Captaincy</h3>
        <p class="text-xs opacity-50 mb-6 font-medium">Send a captain transfer invitation to a team member. They must accept it for the transfer to take effect.</p>
        @if($team->members->count())
            <form action="{{ route('teams.transfer-captain', $team->slug) }}" method="POST"
              x-data="{ open: false, selected: { id: {{ $team->members->first()->user_id ?? 'null' }}, name: '{{ $team->members->first()?->user->firstname }} {{ $team->members->first()?->user->lastname }}' } }">
            @csrf
            <input type="hidden" name="user_id" :value="selected.id">
            <div class="flex gap-3">
                <div class="relative flex-1">
                    <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-sm font-medium hover:border-primary transition-all">
                        <span x-text="selected.name"></span>
                        <svg class="w-4 h-4 opacity-40 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition
                         class="absolute z-20 w-full mt-2 glass border border-white/10 rounded-2xl overflow-hidden">
                        @foreach($team->members as $member)
                        <button type="button"
                                @click="selected = { id: {{ $member->user_id }}, name: '{{ $member->user->firstname }} {{ $member->user->lastname }}' }; open = false"
                                class="w-full text-left px-4 py-3 text-sm hover:bg-white/5 transition-all">
                            {{ $member->user->firstname }} {{ $member->user->lastname }}
                        </button>
                        @endforeach
                    </div>
                </div>
                <button class="px-6 py-3 bg-warning/10 text-warning border border-warning/20 rounded-xl text-xs font-bold hover:bg-warning hover:text-black transition-all">SEND TRANSFER</button>
            </div>
        </form>
        @else
            <p class="text-xs opacity-40">No members to transfer captaincy to.</p>
        @endif

        <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-danger mb-4">Danger Zone</h3>
        <p class="text-xs opacity-50 mb-6 font-medium">Deactivating the team will remove all rosters and data permanently.</p>
        <button class="px-8 py-3 bg-danger/10 text-danger border border-danger/20 rounded-xl text-xs font-bold hover:bg-danger hover:text-white transition-all">DISBAND TEAM</button>
    </div>
</div>
