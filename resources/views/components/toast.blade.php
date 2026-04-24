<div class="fixed bottom-8 right-8 z-[100] flex flex-col gap-4 w-full max-w-sm pointer-events-none">
    <template x-for="toast in $store.global.toasts" :key="toast.id">
        <div x-show="true" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-4 opacity-0 scale-95"
             x-transition:enter-end="translate-y-0 opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             :class="{
                'bg-success/90 border-success shadow-neon-success': toast.type === 'success',
                'bg-danger/90 border-danger shadow-neon-danger': toast.type === 'danger',
                'bg-primary/90 border-primary shadow-neon-primary': toast.type === 'info',
             }"
             class="glass border-2 p-4 rounded-2xl text-white shadow-2xl flex items-center justify-between overflow-hidden relative pointer-events-auto">
            
            <div class="flex items-center gap-3 relative z-10">
                <p class="text-xs font-bold uppercase tracking-widest" x-text="toast.message"></p>
            </div>

            <button @click="$store.global.toasts = $store.global.toasts.filter(t => t.id !== toast.id)" class="p-1 hover:bg-white/10 rounded-lg transition-colors z-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="absolute bottom-0 left-0 h-1 bg-white/20 animate-[toastProgress_5s_linear_forwards]"></div>
        </div>
    </template>
</div>
