<div class="space-y-6">
    <h2 class="text-xl font-display font-black tracking-tight px-2">Pending Applications</h2>
    <div class="space-y-4">
        @forelse($team->invitations as $req)
            <div class="glass p-6 rounded-3xl border border-white/5 flex flex-col md:flex-row items-center justify-between gap-6 group">
                <div class="flex items-center gap-6 flex-1">
                    <img class="w-16 h-16 rounded-2xl bg-dark-bg" src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $req->invitedUser->firstname }}">
                    <div>
                        <h4 class="text-lg font-black uppercase tracking-tight">{{ $req->invitedUser->firstname }} {{ $req->invitedUser->lastname }}</h4>
                        <div class="flex gap-4 mt-1">
                            <span class="text-xs font-bold text-secondary">{{ $req->invitedUser->playerGameProfiles->first()->rank ?? 'Unranked' }}</span>
                            <span class="text-xs font-bold opacity-40">•</span>
                            <span class="text-xs font-bold opacity-60">{{ $req->invitedUser->profile->win_rate ?? 0 }}% Win Rate</span>
                        </div>
                        @if($req->note)
                            <p class="text-xs opacity-40 mt-2 font-medium italic">"{{ $req->note }}"</p>
                        @endif
                    </div>
                </div>
                <div class="flex gap-4">
                     <form action="{{ route('teams.handle-request', $req->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="accepted">
                        <button class="px-8 py-3 bg-success/10 text-success border border-success/20 rounded-xl text-xs font-bold hover:bg-success hover:text-white transition-all shadow-neon-success">ACCEPT</button>
                     </form>
                     <form action="{{ route('teams.handle-request', $req->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="declined">
                        <button class="px-8 py-3 bg-danger/10 text-danger border border-danger/20 rounded-xl text-xs font-bold hover:bg-danger hover:text-white transition-all">DECLINE</button>
                     </form>
                </div>
            </div>
        @empty
            <div class="glass p-12 rounded-[3rem] border-white/5 text-center opacity-40">
                <p class="text-sm font-medium uppercase tracking-widest">No pending join requests</p>
            </div>
        @endforelse
    </div>
</div>
