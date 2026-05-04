<x-layouts.app title="System Logs">

    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <span class="px-3 py-1 rounded-lg bg-rose-500/10 text-rose-400 text-[10px] font-black uppercase tracking-widest border border-rose-500/20">Super Admin</span>
            </div>
            <h1 class="text-2xl font-display font-black uppercase tracking-tight">
                System <span class="text-rose-400">Logs</span>
            </h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">Last {{ count($logs) }} entries — laravel.log</p>
        </div>
        <x-ui.button href="{{ route('admin.system.dashboard') }}" variant="ghost" size="sm">← Back</x-ui.button>
    </div>

    @php
        $levelStyles = [
            'error'     => 'bg-danger/10 text-danger border-danger/20',
            'critical'  => 'bg-danger/20 text-danger border-danger/30',
            'warning'   => 'bg-warning/10 text-warning border-warning/20',
            'info'      => 'bg-primary/10 text-primary border-primary/20',
            'debug'     => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
        ];
    @endphp

    <div class="glass rounded-3xl border border-white/5 overflow-hidden">
        {{-- Filter bar --}}
        <div x-data="{ filter: 'all' }" >
            <div class="flex items-center gap-2 p-4 border-b border-white/5 overflow-x-auto">
                @foreach(['all' => 'All', 'error' => 'Error', 'critical' => 'Critical', 'warning' => 'Warning', 'info' => 'Info', 'debug' => 'Debug'] as $val => $lbl)
                    <button @click="filter = '{{ $val }}'"
                            :class="filter === '{{ $val }}' ? 'bg-primary text-white' : 'text-gray-400 hover:text-white hover:bg-white/5'"
                            class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest transition-all whitespace-nowrap">
                        {{ $lbl }}
                    </button>
                @endforeach
            </div>

            <div class="divide-y divide-white/5 max-h-[70vh] overflow-y-auto">
                @forelse($logs as $log)
                    <div x-show="filter === 'all' || filter === '{{ $log['level'] }}'"
                         class="flex items-start gap-4 px-6 py-4 hover:bg-white/2 transition-all">

                        <span class="px-2 py-1 rounded-lg border text-[10px] font-black uppercase tracking-widest shrink-0 {{ $levelStyles[$log['level']] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20' }}">
                            {{ $log['level'] }}
                        </span>

                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-300 break-all">{{ $log['message'] }}</p>
                        </div>

                        <span class="text-[10px] text-gray-500 shrink-0 font-mono">{{ $log['date'] }}</span>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-500">
                        <p class="text-sm font-bold">No logs found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</x-layouts.app>
