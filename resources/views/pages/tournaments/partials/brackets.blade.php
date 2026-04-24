<div class="overflow-x-auto pb-20 custom-scrollbar">
    <div class="min-w-[1280px] flex items-center justify-between gap-16 pt-12 px-4 relative">
        
        {{-- Quarter Finals --}}
        <div class="space-y-16 flex-1">
             <div class="text-center mb-10">
                <p class="text-[10px] font-black opacity-30 uppercase tracking-[0.4em]">Round of 8</p>
             </div>
             @foreach(range(1, 4) as $i)
                <div class="w-full glass rounded-3xl border border-white/5 p-6 relative group hover:border-primary/40 transition-all duration-500 hover:shadow-2xl">
                     <div class="space-y-4">
                        <div class="flex items-center justify-between opacity-80 group-hover:opacity-100">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded bg-dark-bg border border-white/10 shadow-inner"></div>
                                <span class="text-xs font-black tracking-tight uppercase">TBD</span>
                            </div>
                            <span class="text-xs font-black opacity-30">--</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-white/5 pt-4 opacity-40 group-hover:opacity-60">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded bg-dark-bg border border-white/10 shadow-inner"></div>
                                <span class="text-xs font-black tracking-tight uppercase">TBD</span>
                            </div>
                            <span class="text-xs font-black opacity-30">--</span>
                        </div>
                     </div>
                     <!-- Link to next round -->
                     <div class="absolute top-1/2 -right-16 w-16 h-[1px] bg-white/10 group-hover:bg-primary/30 transition-colors"></div>
                </div>
             @endforeach
        </div>

        {{-- Semi Finals --}}
        <div class="space-y-48 flex-1">
            <div class="text-center mb-10">
                <p class="text-[10px] font-black opacity-30 uppercase tracking-[0.4em]">Semi-Finals</p>
             </div>
            @foreach(range(1, 2) as $i)
                <div class="w-full glass rounded-3xl border border-primary/20 p-6 relative group hover:shadow-neon-primary transition-all duration-500">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between text-primary">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded bg-primary/10 border border-primary/20"></div>
                                <span class="text-xs font-black tracking-tight uppercase">ROUND 1 WINNER</span>
                            </div>
                            <span class="text-xs font-black animate-pulse">--</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-white/5 pt-4 opacity-30">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded bg-dark-bg border border-white/10"></div>
                                <span class="text-xs font-black tracking-tight uppercase">TBD</span>
                            </div>
                        </div>
                     </div>
                     <!-- Connectors -->
                     <div class="absolute top-1/2 -left-16 w-16 h-[1px] bg-primary/20"></div>
                     <div class="absolute top-1/2 -right-16 w-16 h-[1px] bg-white/10 group-hover:bg-secondary/30 transition-colors"></div>
                </div>
            @endforeach
        </div>

        {{-- Grand Finals --}}
        <div class="flex-1 flex flex-col items-center justify-center">
            <div class="text-center mb-10">
                <p class="text-[10px] font-black text-warning uppercase tracking-[0.4em] animate-pulse">The Grand Final</p>
             </div>
            <div class="w-full max-w-[400px] glass rounded-[4rem] border-2 border-warning/30 p-12 relative group shadow-neon-primary animate-float overflow-hidden">
                <div class="absolute inset-0 bg-warning/5 opacity-20 group-hover:opacity-40 transition-opacity"></div>
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-warning to-transparent"></div>
                
                <div class="space-y-16 relative z-10 text-center">
                    <div class="space-y-6">
                        <div class="w-24 h-24 bg-warning/10 rounded-[2rem] flex items-center justify-center mx-auto shadow-neon-primary border border-warning/30 group-hover:scale-110 transition-transform duration-700">
                             <svg class="w-12 h-12 text-warning" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1a1 1 0 112 0v1a1 1 0 11-2 0zM13 16v-1a1 1 0 112 0v1a1 1 0 11-2 0zM14.95 14.95a1 1 0 101.414-1.414l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM6.464 14.95a1 1 0 10-1.414-1.414l.707.707a1 1 0 001.414-1.414l-.707-.707z" /></svg>
                        </div>
                        <h4 class="text-2xl font-display font-black tracking-tighter uppercase line-clamp-1">World Championship</h4>
                    </div>

                    <div class="space-y-6">
                         <div class="glass p-6 rounded-3xl border border-white/5 opacity-30 grayscale group-hover:grayscale-0 transition-all">
                            <p class="text-[10px] font-black uppercase opacity-40 mb-2 tracking-widest">Finalist A</p>
                            <p class="text-xl font-black tracking-tighter">TBD</p>
                         </div>
                         <div class="flex items-center justify-center gap-4">
                            <div class="h-[1px] flex-1 bg-white/5"></div>
                            <div class="text-xs font-black text-warning">VS</div>
                            <div class="h-[1px] flex-1 bg-white/5"></div>
                         </div>
                         <div class="glass p-6 rounded-3xl border border-white/5 opacity-30 grayscale group-hover:grayscale-0 transition-all">
                            <p class="text-[10px] font-black uppercase opacity-40 mb-2 tracking-widest">Finalist B</p>
                            <p class="text-xl font-black tracking-tighter">TBD</p>
                         </div>
                    </div>
                </div>
                
                <!-- Connector -->
                <div class="absolute top-1/2 -left-16 w-16 h-[1px] bg-white/10"></div>
            </div>
        </div>

    </div>
</div>
