<div class="max-w-2xl mx-auto glass p-10 rounded-[3rem] border border-white/5">
    <form action="{{ route('teams.update', $team->id) }}" method="POST" class="space-y-8">
        @csrf @method('PATCH')
        
        <div class="space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-[0.2em] opacity-40">Identity</h3>
            <div class="space-y-4">
                <div class="space-y-1.5 px-1">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Team Name</label>
                    <input type="text" name="name" value="{{ $team->name }}" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-medium focus:outline-none focus:border-primary transition-all">
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

    <div class="mt-12 pt-12 border-t border-white/5">
        <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-danger mb-4">Danger Zone</h3>
        <p class="text-xs opacity-50 mb-6 font-medium">Deactivating the team will remove all rosters and data permanently.</p>
        <button class="px-8 py-3 bg-danger/10 text-danger border border-danger/20 rounded-xl text-xs font-bold hover:bg-danger hover:text-white transition-all">DISBAND TEAM</button>
    </div>
</div>
