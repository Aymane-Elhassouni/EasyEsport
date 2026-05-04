<div class="space-y-4">
    <h2 class="text-xl font-display font-black tracking-tight px-2">Join Requests</h2>
    @php $joinRequests = $team->invitations->where('status', 'pending')->where('type', 'join_request'); @endphp
    @forelse($joinRequests as $req)
        <div class="glass p-6 rounded-3xl border border-white/5 flex flex-col md:flex-row items-center justify-between gap-6"
             x-data="{ loading: false }"
             x-ref="card_{{ $req->id }}">
            <div class="flex items-center gap-6 flex-1">
                <img class="w-16 h-16 rounded-2xl object-cover bg-dark-bg" src="{{ $req->invitedUser->avatar_url }}">
                <div>
                    <h4 class="text-lg font-black uppercase tracking-tight">{{ $req->invitedUser->firstname }} {{ $req->invitedUser->lastname }}</h4>
                    <div class="flex gap-4 mt-1">
                        <span class="text-xs font-bold text-secondary">{{ $req->invitedUser->playerGameProfiles->first()->rank ?? 'Unranked' }}</span>
                        <span class="text-xs font-bold opacity-40">•</span>
                        <span class="text-xs font-bold opacity-60">{{ $req->invitedUser->profile->win_rate ?? 0 }}% Win Rate</span>
                    </div>
                    <p class="text-xs opacity-40 mt-1">Requested {{ $req->sent_at->diffForHumans() }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button :disabled="loading"
                    @click="
                        loading = true;
                        fetch('{{ route('teams.handle-request', $req->id) }}', {
                            method: 'PATCH',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({ status: 'accepted' })
                        }).then(r => r.ok && $el.closest('[x-ref=card_{{ $req->id }}]').remove())
                    "
                    class="px-8 py-3 bg-success/10 text-success border border-success/20 rounded-xl text-xs font-bold hover:bg-success hover:text-white transition-all disabled:opacity-50">ACCEPT</button>
                <button :disabled="loading"
                    @click="
                        loading = true;
                        fetch('{{ route('teams.handle-request', $req->id) }}', {
                            method: 'PATCH',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({ status: 'rejected' })
                        }).then(r => r.ok && $el.closest('[x-ref=card_{{ $req->id }}]').remove())
                    "
                    class="px-8 py-3 bg-danger/10 text-danger border border-danger/20 rounded-xl text-xs font-bold hover:bg-danger hover:text-white transition-all disabled:opacity-50">DECLINE</button>
            </div>
        </div>
    @empty
        <div class="glass p-12 rounded-[3rem] border-white/5 text-center opacity-40">
            <p class="text-sm font-medium uppercase tracking-widest">No pending join requests</p>
        </div>
    @endforelse
</div>
