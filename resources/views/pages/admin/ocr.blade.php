<x-layouts.app title="Admin OCR Review">

    <div class="space-y-8" x-data="{ currentStatus: 'PENDING' }">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-4xl font-display font-black tracking-tight">ARBITRATION PANEL</h1>
                <p class="text-gray-500">Reviewing Dispute <span class="text-primary font-bold">#DS-8842</span> • Valorant Premier</p>
            </div>
            <span :class="{
                'bg-warning/10 text-warning border-warning/20': currentStatus === 'PENDING',
                'bg-success/10 text-success border-success/20': currentStatus === 'VALIDATED',
                'bg-danger/10 text-danger border-danger/20': currentStatus === 'DISPUTED'
            }" class="px-4 py-1.5 rounded-xl border text-xs font-black uppercase tracking-widest transition-all duration-500">
                Status: <span x-text="currentStatus"></span>
            </span>
        </div>

        {{-- Screenshot Comparison --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div class="flex items-center justify-between px-2">
                    <h3 class="font-bold text-sm uppercase tracking-widest opacity-60">Player Screenshot</h3>
                    <span class="text-[10px] font-bold text-primary">Confidence: 98%</span>
                </div>
                <div class="relative group cursor-crosshair overflow-hidden rounded-3xl border-2 border-white/10 glass shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=1200"
                         class="w-full h-[400px] object-cover transition-transform duration-700 group-hover:scale-150 transform-gpu origin-center">
                    <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black/80 to-transparent">
                        <p class="text-white text-xs font-bold">Submitted by <span class="text-primary">Alex_Pro</span></p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between px-2">
                    <h3 class="font-bold text-sm uppercase tracking-widest opacity-60">Opponent Screenshot</h3>
                    <span class="text-[10px] font-bold text-danger">Confidence: 45% (Mismatch)</span>
                </div>
                <div class="relative group cursor-crosshair overflow-hidden rounded-3xl border-2 border-danger/30 glass shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&q=80&w=1200"
                         class="w-full h-[400px] object-cover transition-transform duration-700 group-hover:scale-150 transform-gpu origin-center">
                    <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black/80 to-transparent">
                        <p class="text-white text-xs font-bold">Submitted by <span class="text-danger">Rival_Squad</span></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Verdict --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 glass rounded-[2.5rem] p-8 border border-danger/20">
                <h3 class="text-2xl font-display font-black mb-6">OCR Conflict Detected</h3>
                <div class="grid grid-cols-3 gap-4 text-center mb-4">
                    <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                        <p class="text-[10px] font-bold opacity-50 uppercase mb-1">OCR Analysis</p>
                        <p class="text-3xl font-black text-primary">15 - 3</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                        <p class="text-[10px] font-bold opacity-50 uppercase mb-1">Alex_Pro claim</p>
                        <p class="text-3xl font-black text-success">15 - 3</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-danger/10 border border-danger/20">
                        <p class="text-[10px] font-bold text-danger uppercase mb-1">Rival claim</p>
                        <p class="text-3xl font-black text-danger">13 - 13</p>
                    </div>
                </div>
                <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-200 text-xs font-medium">
                    The opponent screenshot appears to be edited or from a different match session. OCR visibility is low (45%).
                </div>
            </div>

            <div class="space-y-4">
                <h4 class="text-sm font-bold uppercase tracking-widest opacity-50 px-2">Final Verdict</h4>

                @foreach([
                    ['status' => 'VALIDATED', 'label' => 'VALIDATE',        'sub' => 'Confirm OCR result',       'color' => 'success'],
                    ['status' => 'PENDING',   'label' => 'FURTHER REVIEW',   'sub' => 'Flag for senior admin',    'color' => 'primary'],
                    ['status' => 'DISPUTED',  'label' => 'REJECT & DISPUTE', 'sub' => 'Invalidate match result',  'color' => 'danger'],
                ] as $verdict)
                    <button @click="currentStatus = '{{ $verdict['status'] }}'"
                            class="w-full p-6 glass rounded-2xl border border-{{ $verdict['color'] }}/30 hover:bg-{{ $verdict['color'] }}/20 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-{{ $verdict['color'] }} text-white rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="font-black leading-none">{{ $verdict['label'] }}</p>
                                <p class="text-[10px] font-bold opacity-60 mt-0.5">{{ $verdict['sub'] }}</p>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

    </div>

</x-layouts.app>
