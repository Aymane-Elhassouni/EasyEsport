<x-card title="OCR Result Verification" class="max-w-2xl mx-auto">
    <x-slot name="icon">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
    </x-slot>

    <div x-data="ocrScanner" class="space-y-6">
        
        <!-- Upload Area -->
        <div x-show="!isScanning && !isComplete" 
             @dragover.prevent="isDragging = true" 
             @dragleave.prevent="isDragging = false"
             @drop.prevent="isDragging = false; handleFile($event)"
             :class="isDragging ? 'border-primary bg-primary/5' : 'border-dashed border-light-border dark:border-white/20'"
             class="border-2 rounded-2xl p-10 text-center transition-all cursor-pointer hover:border-primary/50 relative overflow-hidden group">
            
            <input type="file" x-ref="fileInput" @change="handleFile" class="hidden" accept="image/*">
            
            <template x-if="!previewUrl">
                <div class="space-y-4">
                    <div class="w-16 h-16 bg-dark-surface dark:bg-white/5 rounded-full flex items-center justify-center mx-auto text-gray-500 group-hover:text-primary transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    </div>
                    <div>
                        <p class="font-bold text-lg">Drop screenshot here</p>
                        <p class="text-xs opacity-50 uppercase tracking-widest mt-1">or click to browse</p>
                    </div>
                    <button @click="$refs.fileInput.click()" class="btn-primary mt-4 py-2 px-6">Browse Files</button>
                </div>
            </template>

            <template x-if="previewUrl">
                <div class="space-y-4">
                    <img :src="previewUrl" class="max-h-64 mx-auto rounded-xl shadow-2xl object-cover">
                    <div class="flex items-center justify-center gap-3 mt-4">
                        <button @click.stop="reset()" class="px-4 py-2 text-xs font-bold bg-danger/10 text-danger rounded-lg hover:bg-danger/20 transition-all">Cancel</button>
                        <button @click.stop="startScan()" class="btn-premium bg-success text-white shadow-neon-success hover:shadow-cyan-glow">Start Scan</button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Scanning State -->
        <div x-show="isScanning" class="py-12 space-y-8 animate-fade-in text-center">
            <div class="relative w-48 h-48 mx-auto rounded-2xl border border-white/10 overflow-hidden bg-dark-bg p-2 shadow-2xl">
                <img :src="previewUrl" class="w-full h-full object-cover rounded-xl opacity-60">
                <div class="scan-line"></div>
            </div>
            <div class="space-y-4 max-w-sm mx-auto">
                <p class="text-xs font-bold text-primary uppercase tracking-widest animate-pulse">Extracting Match Data...</p>
                <div class="w-full h-1.5 bg-dark-surface dark:bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-primary shadow-neon-primary transition-all duration-300" :style="`width: ${progress}%`"></div>
                </div>
            </div>
        </div>

        <!-- Result State -->
        <div x-show="isComplete" class="space-y-6 animate-slide-up" x-cloak>
            <div class="p-6 bg-success/10 border border-success/20 rounded-2xl flex flex-col md:flex-row items-center gap-6">
                <div class="w-16 h-16 bg-success/20 text-success rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div class="flex-1 space-y-2 text-center md:text-left">
                    <p class="text-xl font-bold font-display">Data Successfully Extracted</p>
                    <p class="text-xs font-bold opacity-60 uppercase tracking-widest" x-text="`Confidence: ${result?.confidence}`"></p>
                </div>
                <button @click="confirmSave()" class="btn-primary w-full md:w-auto">Confirm & Save</button>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="p-4 bg-dark-surface dark:bg-white/5 rounded-xl border border-white/5">
                    <p class="text-[10px] font-bold opacity-50 uppercase tracking-widest mb-1">Player</p>
                    <p class="font-bold text-sm" x-text="result?.playerName"></p>
                </div>
                <div class="p-4 bg-dark-surface dark:bg-white/5 rounded-xl border border-white/5">
                    <p class="text-[10px] font-bold opacity-50 uppercase tracking-widest mb-1">Rank Detected</p>
                    <p class="font-bold text-sm text-primary" x-text="result?.rank"></p>
                </div>
                <div class="p-4 bg-dark-surface dark:bg-white/5 rounded-xl border border-white/5 col-span-2 md:col-span-1">
                    <p class="text-[10px] font-bold opacity-50 uppercase tracking-widest mb-1">Stats Extracted</p>
                    <p class="font-bold text-sm text-success" x-text="result?.stats"></p>
                </div>
            </div>
            
            <button @click="reset()" class="text-xs font-bold text-gray-500 hover:text-primary underline w-full text-center">Scan Another Image</button>
        </div>
    </div>
</x-card>