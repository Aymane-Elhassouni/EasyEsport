<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
    
    <!-- Left Column: Recent Matches -->
    <div class="lg:col-span-2 space-y-6">
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-xl font-display font-bold">Recent Match History</h2>
            <button class="text-xs font-bold text-primary hover:underline">View All Matches</button>
        </div>

        <div class="space-y-4">
            @foreach($mockMatches ?? [] as $match)
            <div class="glass border border-white/5 rounded-2xl p-4 flex items-center justify-between group hover:bg-white/5 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-dark-surface dark:to-dark-border flex items-center justify-center overflow-hidden">
                        <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ $match['opponent'] }}" alt="opponent" class="w-8 h-8 opacity-80">
                    </div>
                    <div>
                        <p class="text-sm font-bold">{{ $match['opponent'] }}</p>
                        <p class="text-[10px] opacity-50 uppercase font-bold">{{ $match['time'] }} • Valorant Premier</p>
                    </div>
                </div>
                
                <div class="text-center px-6">
                    <p class="text-xl font-display font-black tracking-widest">{{ $match['score'] }}</p>
                </div>

                <div class="flex items-center gap-4">
                    @if($match['status'] == 'win')
                        <span class="px-3 py-1 bg-success/10 text-success text-[10px] font-bold rounded-lg border border-success/20 uppercase tracking-widest shadow-neon-success">Victory</span>
                    @elseif($match['status'] == 'loss')
                        <span class="px-3 py-1 bg-danger/10 text-danger text-[10px] font-bold rounded-lg border border-danger/20 uppercase tracking-widest">Defeat</span>
                    @else
                        <span class="px-3 py-1 bg-warning/10 text-warning text-[10px] font-bold rounded-lg border border-warning/20 uppercase tracking-widest animate-pulse-soft">Pending</span>
                    @endif
                    
                    <button class="p-2 opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white/10 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Right Column: OCR Quick Scan & Quick Links -->
    <div class="space-y-6">
        <h2 class="text-xl font-display font-bold">Fast Validation</h2>
        
        <!-- Quick OCR Card -->
        <div x-data="ocrModule" class="relative group">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-secondary/20 blur-2xl rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative glass border border-primary/20 rounded-3xl p-6 text-center space-y-4 overflow-hidden">
                
                <div x-show="!isScanning && !isComplete" class="space-y-4 py-8 animate-fade-in">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto text-primary">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    </div>
                    <div>
                        <p class="font-bold">Screenshot Scan</p>
                        <p class="text-[10px] opacity-50 uppercase font-bold px-4">Upload match screenshot for instant validation</p>
                    </div>
                    <button @click="$refs.fileInput.click()" class="btn-primary w-full shadow-neon-primary text-sm py-3">Select Image</button>
                    <input type="file" x-ref="fileInput" class="hidden" @change="handleFile">
                </div>

                <!-- Scanning State -->
                <div x-show="isScanning" class="space-y-6 py-8 animate-fade-in">
                    <div class="relative w-32 h-32 mx-auto rounded-2xl border border-white/10 overflow-hidden bg-dark-bg">
                        <img :src="previewUrl" class="w-full h-full object-cover opacity-50">
                        <div class="scan-line"></div>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm font-bold text-secondary animate-pulse">OCR ANALYZING DATA...</p>
                        <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full bg-secondary shadow-[0_0_15px_rgba(34,211,238,0.4)] transition-all duration-300" 
                                 :style="`width: ${progress}%` "></div>
                        </div>
                    </div>
                </div>

                <!-- Result State -->
                <div x-show="isComplete" x-transition class="space-y-4 py-4 animate-slide-up text-left">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold opacity-50 uppercase tracking-widest">Match Result</span>
                        <span class="px-2 py-0.5 bg-success/20 text-success text-[10px] font-bold rounded border border-success/30">VALIDATED</span>
                    </div>
                    <div class="flex justify-between items-center bg-white/5 p-4 rounded-2xl border border-white/5">
                        <span class="text-3xl font-display font-black" x-text="result?.score || ''"></span>
                        <div class="text-right">
                            <p class="text-[10px] font-bold opacity-50 uppercase">Confidence</p>
                            <p class="text-sm font-bold text-success" x-text="result ? `${Math.round(result.confidence * 100)}% Match` : ''"></p>
                        </div>
                    </div>

                    <button @click="isComplete = false; previewUrl = null" class="w-full py-2.5 rounded-xl border border-white/10 text-xs font-bold hover:bg-white/5 transition-all mt-4">Close Report</button>
                </div>

            </div>
        </div>

        <!-- Active Players -->
        <div class="glass border border-white/5 rounded-3xl p-6">
            <h3 class="text-sm font-bold mb-4 flex items-center gap-2">
                <span class="w-2 h-2 bg-success rounded-full"></span>
                Team Roster (Online)
            </h3>
            <div class="space-y-3">
                @foreach(['Alex_Pistol', 'GhostWarrior', 'NeonVibe', 'Elite_Sniper'] as $player)
                <div class="flex items-center justify-between p-2 hover:bg-white/5 rounded-xl transition-colors cursor-pointer group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg overflow-hidden border border-white/10 bg-dark-surface p-1">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $player }}" alt="p">
                        </div>
                        <span class="text-sm font-medium">{{ $player }}</span>
                    </div>
                    <div class="opacity-0 group-hover:opacity-100 flex items-center gap-2 transition-opacity">
                         <div class="w-6 h-6 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.827-1.213L3 20l1.397-3.413A8.706 8.706 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                         </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
