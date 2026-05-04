<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    @foreach($tournament->groups->load(['teamStats.team']) as $group)
        <div class="glass rounded-[2.5rem] border border-white/5 overflow-hidden shadow-2xl">
            <div class="px-8 py-6 border-b border-white/5 bg-white/5 flex items-center justify-between">
                <h3 class="font-display font-black text-xl uppercase tracking-tighter">{{ $group->name }}</h3>
                <span class="text-[10px] font-bold opacity-30 uppercase tracking-widest">Group Standings</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-widest opacity-30 border-b border-white/5">
                            <th class="px-8 py-4">#</th>
                            <th class="px-4 py-4">Team</th>
                            <th class="px-4 py-4 text-center">P</th>
                            <th class="px-4 py-4 text-center">W</th>
                            <th class="px-4 py-4 text-center">D</th>
                            <th class="px-4 py-4 text-center">L</th>
                            <th class="px-4 py-4 text-center">+/-</th>
                            <th class="px-8 py-4 text-right">PTS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($group->teamStats()->orderByDesc('points')->orderByDesc('score_diff')->get() as $index => $stat)
                            @php
                                $isQualifier = $index < $tournament->qualifiers_per_group;
                                $rowClass = $isQualifier ? 'bg-success/5' : '';
                                $rankClass = $isQualifier ? 'text-success' : 'text-gray-500';
                            @endphp
                            <tr class="group hover:bg-white/5 transition-all {{ $rowClass }}">
                                <td class="px-8 py-5 font-black text-xs {{ $rankClass }}">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-4 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-6 h-6 rounded-lg bg-dark-bg border border-white/10 flex items-center justify-center overflow-hidden">
                                            <img src="https://api.dicebear.com/7.x/identicon/svg?seed={{ $stat->team->name }}" class="w-full h-full">
                                        </div>
                                        <span class="text-xs font-bold">{{ $stat->team->name }}</span>
                                        @if($isQualifier)
                                            <span class="text-[8px] bg-success/20 text-success px-1.5 py-0.5 rounded uppercase font-black tracking-tighter">Qualified</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-5 text-xs font-bold text-center opacity-60">{{ $stat->played }}</td>
                                <td class="px-4 py-5 text-xs font-bold text-center text-success/60">{{ $stat->wins }}</td>
                                <td class="px-4 py-5 text-xs font-bold text-center opacity-40">{{ $stat->draws }}</td>
                                <td class="px-4 py-5 text-xs font-bold text-center text-danger/60">{{ $stat->losses }}</td>
                                <td class="px-4 py-5 text-xs font-bold text-center {{ $stat->score_diff >= 0 ? 'text-primary' : 'text-danger' }}">
                                    {{ $stat->score_diff > 0 ? '+' : '' }}{{ $stat->score_diff }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span class="text-sm font-black text-primary shadow-neon-primary">{{ $stat->points }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-8 py-4 bg-black/20 text-[9px] font-bold text-gray-500 flex items-center gap-4 uppercase tracking-widest">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-success"></span>
                    <span>Qualification Zone</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-white/10"></span>
                    <span>Elimination Zone</span>
                </div>
            </div>
        </div>
    @endforeach
</div>
