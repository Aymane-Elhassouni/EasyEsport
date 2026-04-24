<div class="space-y-6">
    <h2 class="text-xl font-display font-black tracking-tight px-2">Active Members ({{ $team->members->count() }}/6)</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        {{-- Captain Item --}}
        <div class="glass p-6 rounded-3xl border border-primary/20 flex items-center justify-between group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-primary/5 blur-xl"></div>
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img class="w-12 h-12 rounded-xl bg-dark-bg" src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $team->captain->firstname }}">
                    <div class="absolute -top-1 -right-1 w-5 h-5 bg-warning text-white rounded-md flex items-center justify-center shadow-lg border-2 border-dark-bg">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-sm">{{ $team->captain->firstname }} {{ $team->captain->lastname }} @if(Auth::id() === $team->captain_id) <span class="text-xs text-primary">(You)</span> @endif</h4>
                    <p class="text-[10px] font-bold text-primary uppercase tracking-widest">Captain / Founder</p>
                </div>
            </div>
        </div>

        {{-- Members Loop --}}
        @foreach($team->members as $member)
            @if($member->user_id !== $team->captain_id)
                <div class="glass p-6 rounded-3xl border border-white/5 flex items-center justify-between group hover:border-primary/30 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <img class="w-12 h-12 rounded-xl bg-dark-bg" src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $member->user->firstname }}">
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">{{ $member->user->firstname }} {{ $member->user->lastname }} @if(Auth::id() === $member->user_id) <span class="text-xs text-primary">(You)</span> @endif</h4>
                            <p class="text-[10px] font-bold opacity-50 uppercase tracking-widest">Active Member</p>
                        </div>
                    </div>

                    @if(Auth::id() === $team->captain_id)
                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <form action="{{ route('teams.kick', ['team' => $team->id, 'user' => $member->user_id]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 hover:bg-danger/20 rounded-lg text-danger transition-colors" title="Kick member">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path></svg>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
        
        {{-- Open Slots --}}
        @for($i = $team->members->count(); $i < 6; $i++)
            <div class="border-2 border-dashed border-white/5 p-6 rounded-3xl flex items-center justify-center group hover:border-primary/50 transition-all cursor-pointer">
                <div class="text-center">
                    <div class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center text-white/20 group-hover:bg-primary group-hover:text-white transition-all mx-auto mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <p class="text-[10px] font-bold opacity-30 uppercase tracking-widest group-hover:opacity-100 group-hover:text-primary transition-all">Empty Slot</p>
                </div>
            </div>
        @endfor
    </div>
</div>
