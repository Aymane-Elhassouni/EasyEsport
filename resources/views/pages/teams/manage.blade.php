<x-layouts.app>
    <x-slot name="title">Team Management HQ</x-slot>

    <div class="space-y-8 animate-fade-in" x-data="{ activeTab: 'roster' }" data-team-id="{{ $team->id }}">
        
        @include('pages.teams.partials.header', ['team' => \App\Presenters\TeamPresenter::make($team)])

        <div>
            <div x-show="activeTab === 'roster'" x-transition>
                @include('pages.teams.partials.roster', ['team' => $team])
            </div>
            <div x-show="activeTab === 'requests'" x-transition>
                @include('pages.teams.partials.requests', ['team' => $team])
            </div>
            <div x-show="activeTab === 'settings'" x-transition>
                @include('pages.teams.partials.settings', ['team' => $team])
            </div>
        </div>

    </div>

    {{-- Join Request Modal --}}
    @if(request('request') && ($joinReq = \App\Models\Invitation::with('invitedUser')->where('id', request('request'))->where('status', 'pending')->first()))
    <div x-data="{ open: true, loading: false }" x-show="open" x-transition
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60" @click.self="open = false">
        <div class="glass border border-white/10 rounded-3xl p-8 w-full max-w-md space-y-6">
            <h3 class="text-lg font-black uppercase tracking-widest">Join Request</h3>
            <div class="flex items-center gap-4">
                <img src="{{ $joinReq->invitedUser->avatar_url }}" class="w-16 h-16 rounded-2xl object-cover">
                <div>
                    <p class="font-black text-lg">{{ $joinReq->invitedUser->firstname }} {{ $joinReq->invitedUser->lastname }}</p>
                    <p class="text-xs opacity-50">{{ $joinReq->invitedUser->email }}</p>
                    <p class="text-xs opacity-40 mt-1">Requested {{ $joinReq->sent_at->diffForHumans() }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button :disabled="loading"
                    @click="
                        loading = true;
                        fetch('{{ route('teams.handle-request', $joinReq->id) }}', {
                            method: 'PATCH',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({ status: 'accepted' })
                        }).then(r => r.ok && (open = false))
                    "
                    class="flex-1 py-3 bg-success/10 text-success border border-success/20 rounded-xl text-xs font-bold hover:bg-success hover:text-white transition-all disabled:opacity-50">ACCEPT</button>
                <button :disabled="loading"
                    @click="
                        loading = true;
                        fetch('{{ route('teams.handle-request', $joinReq->id) }}', {
                            method: 'PATCH',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({ status: 'rejected' })
                        }).then(r => r.ok && (open = false))
                    "
                    class="flex-1 py-3 bg-danger/10 text-danger border border-danger/20 rounded-xl text-xs font-bold hover:bg-danger hover:text-white transition-all disabled:opacity-50">DECLINE</button>
            </div>
        </div>
    </div>
    @endif

    @vite('resources/js/team-channel.js')

</x-layouts.app>
