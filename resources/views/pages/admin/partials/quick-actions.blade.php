<x-card title="Administrative Hub" icon='<path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />'>
    <div class="grid grid-cols-1 gap-3">
        @foreach([
            ['label' => 'Validate OCR Results', 'route' => 'admin.ocr',          'color' => 'primary'],
            ['label' => 'Manage Disputes',       'route' => 'admin.disputes',     'color' => 'warning'],
            ['label' => 'User Management',       'route' => 'admin.users',        'color' => 'secondary'],
            ['label' => 'Tournament Logs',       'route' => 'admin.system.logs',  'color' => 'primary'],
        ] as $action)
            <a href="{{ route($action['route']) }}"
               class="flex items-center justify-between p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-{{ $action['color'] }}/10 hover:border-{{ $action['color'] }}/30 transition-all group">
                <span class="text-[10px] font-black uppercase tracking-widest group-hover:text-{{ $action['color'] }} transition-colors">{{ $action['label'] }}</span>
                <svg class="w-4 h-4 opacity-20 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endforeach
    </div>

    <div class="mt-8 p-6 bg-primary/5 border border-primary/20 rounded-[2rem] space-y-3">
        <h4 class="text-[10px] font-black text-primary uppercase tracking-widest">Platform Snapshot</h4>
        <div class="flex justify-between text-xs font-bold mb-1">
            <span class="opacity-40">System Health</span>
            <span class="text-success">EXCELLENT</span>
        </div>
        <div class="w-full h-1 bg-white/10 rounded-full overflow-hidden">
            <div class="h-full bg-success w-[94%]"></div>
        </div>
    </div>
</x-card>
