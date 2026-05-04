<div class="space-y-4">
    <div class="flex items-center justify-between px-2">
        <h2 class="text-lg font-display font-black tracking-tight uppercase">Pending Registrations</h2>
        <span class="text-xs font-bold opacity-40 uppercase tracking-widest">
            {{ $tournament->registrations->where('status', 'pending')->count() }} pending
        </span>
    </div>

    @forelse($tournament->registrations->where('status', 'pending') as $reg)
        <div class="glass p-5 rounded-2xl border border-white/5 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <img src="{{ $reg->team->logo_url }}" class="w-12 h-12 rounded-xl border border-white/10">
                <div>
                    <p class="font-black uppercase tracking-tight">{{ $reg->team->name }}</p>
                    <p class="text-xs text-gray-400">{{ $reg->team->members->count() }} members · Captain: {{ $reg->team->captain?->firstname }} {{ $reg->team->captain?->lastname }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('admin.applications.validate', $tournament->slug) }}">
                    @csrf
                    <input type="hidden" name="registration_id" value="{{ $reg->id }}">
                    <input type="hidden" name="status" value="approved">
                    <button class="px-4 py-2 bg-success/10 text-success border border-success/20 rounded-xl text-xs font-bold hover:bg-success hover:text-white transition-all">
                        Approve
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.applications.validate', $tournament->slug) }}">
                    @csrf
                    <input type="hidden" name="registration_id" value="{{ $reg->id }}">
                    <input type="hidden" name="status" value="rejected">
                    <button class="px-4 py-2 bg-danger/10 text-danger border border-danger/20 rounded-xl text-xs font-bold hover:bg-danger hover:text-white transition-all">
                        Reject
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="glass p-10 rounded-2xl border border-white/5 text-center opacity-40">
            <p class="text-sm font-bold uppercase tracking-widest">No pending registrations</p>
        </div>
    @endforelse
</div>
