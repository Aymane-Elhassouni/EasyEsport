@php
$cards = [
    [
        'label'      => 'Total<br>Users',
        'value'      => number_format($total_users ?? 0),
        'sub'        => 'Platform<br>Growth<br>Active',
        'sub_color'  => 'text-green-500',
        'num_color'  => 'text-blue-500',
        'icon_bg'    => 'bg-[#283046]',
        'icon_color' => 'text-purple-400',
        'icon'       => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
    ],
    [
        'label'      => 'Active<br>Teams',
        'value'      => number_format($total_teams ?? 0),
        'sub'        => 'Across<br>All<br>Games',
        'sub_color'  => 'text-slate-500',
        'num_color'  => 'text-cyan-400',
        'icon_bg'    => 'bg-[#112d42]',
        'icon_color' => 'text-cyan-400',
        'icon'       => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M19 8a4 4 0 0 1 0 7.75"/>',
    ],
    [
        'label'      => 'Live<br>Tournament',
        'value'      => $active_tournaments ?? 0,
        'sub'        => 'Currently<br>Running',
        'sub_color'  => 'text-blue-500',
        'num_color'  => 'text-yellow-500',
        'icon_bg'    => 'bg-[#3b3424]',
        'icon_color' => 'text-yellow-500',
        'icon'       => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
    ],
    [
        'label'      => 'Pending<br>Disputes',
        'value'      => $pending_disputes ?? 0,
        'sub'        => ($pending_disputes ?? 0) > 0 ? 'Action<br>Required' : 'All<br>Clear',
        'sub_color'  => ($pending_disputes ?? 0) > 0 ? 'text-yellow-400' : 'text-green-500',
        'num_color'  => 'text-rose-400',
        'icon_bg'    => 'bg-[#3e2731]',
        'icon_color' => 'text-rose-400',
        'icon'       => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
    ],
];
@endphp

<div class="grid grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    @foreach($cards as $card)
        <div class="bg-[#161d31] rounded-[60px] p-8 xl:p-12 flex flex-col items-center text-center
                    border border-white/5 shadow-2xl transition-all duration-300
                    hover:-translate-y-1 hover:border-white/10 group">

            <div class="p-4 xl:p-5 rounded-2xl xl:rounded-3xl mb-8 xl:mb-12 {{ $card['icon_bg'] }} {{ $card['icon_color'] }}
                        group-hover:scale-110 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    {!! $card['icon'] !!}
                </svg>
            </div>

            <span class="text-7xl xl:text-9xl font-black italic tracking-tighter {{ $card['num_color'] }} mb-6 xl:mb-10 leading-none">
                {{ $card['value'] }}
            </span>

            <div class="space-y-4 xl:space-y-8 w-full">
                <p class="text-white font-black text-base xl:text-xl tracking-[0.1em] uppercase leading-tight">
                    {!! $card['label'] !!}
                </p>
                <p class="text-xs xl:text-sm font-bold uppercase leading-relaxed tracking-widest {{ $card['sub_color'] }}">
                    {!! $card['sub'] !!}
                </p>
            </div>

        </div>
    @endforeach
</div>
