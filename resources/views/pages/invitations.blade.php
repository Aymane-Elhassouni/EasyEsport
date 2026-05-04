<x-layouts.app>
    <x-slot name="title">My Invitations</x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <h1 class="text-2xl font-display font-black tracking-tight uppercase">Team Invitations</h1>

        <div id="invitations-container" class="space-y-6">
            @forelse($invitations as $invitation)
                <div data-invitation-id="{{ $invitation->id }}" class="glass p-6 rounded-3xl border border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <img src="{{ $invitation->team->logo_url }}" class="w-14 h-14 rounded-2xl object-cover border border-white/10">
                        <div>
                            <h4 class="font-black text-lg uppercase tracking-tight">{{ $invitation->team->name }}</h4>
                            @if($invitation->type === 'captain_transfer')
                                <span class="inline-block text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 bg-warning/10 text-warning border border-warning/20 rounded-lg mb-1">👑 Captain Transfer</span>
                                <p class="text-xs opacity-50 mt-1">You are being offered the captaincy of this team</p>
                            @else
                                <p class="text-xs opacity-50 mt-1">Captain: {{ $invitation->team->captain->firstname }} {{ $invitation->team->captain->lastname }}</p>
                            @endif
                            <p class="text-xs opacity-40 mt-1">Invited {{ $invitation->sent_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3" x-data="{ loading: false }">
                        <button :disabled="loading"
                            @click="loading=true; fetch('{{ route('teams.handle-request', $invitation->id) }}', { method:'PATCH', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json','Accept':'application/json'}, body: JSON.stringify({status:'accepted'}) }).then(r => r.ok && $el.closest('[data-invitation-id]').remove())"
                            class="px-6 py-3 bg-success/10 text-success border border-success/20 rounded-xl text-xs font-bold hover:bg-success hover:text-white transition-all disabled:opacity-50">ACCEPT</button>
                        <button :disabled="loading"
                            @click="loading=true; fetch('{{ route('teams.handle-request', $invitation->id) }}', { method:'PATCH', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json','Accept':'application/json'}, body: JSON.stringify({status:'rejected'}) }).then(r => r.ok && $el.closest('[data-invitation-id]').remove())"
                            class="px-6 py-3 bg-danger/10 text-danger border border-danger/20 rounded-xl text-xs font-bold hover:bg-danger hover:text-white transition-all disabled:opacity-50">DECLINE</button>
                    </div>
                </div>
            @empty
                <div class="glass p-12 rounded-3xl border border-white/5 text-center opacity-40">
                    <p class="text-sm font-medium uppercase tracking-widest">No pending invitations</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
