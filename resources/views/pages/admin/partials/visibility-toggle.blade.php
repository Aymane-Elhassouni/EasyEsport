<div x-data="{ status: '{{ $current ?? 'public' }}' }">
    <input type="hidden" name="status" :value="status">
    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Visibility</label>
    <div class="flex items-center gap-1 p-1 bg-white/5 border border-white/10 rounded-xl w-fit">
        <button type="button" @click="status = 'public'"
                :class="status === 'public' ? 'bg-success/20 text-success' : 'text-gray-500 hover:text-gray-300'"
                class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all">
            <span class="w-1.5 h-1.5 rounded-full" :class="status === 'public' ? 'bg-success' : 'bg-gray-500'"></span>
            Public
        </button>
        <button type="button" @click="status = 'private'"
                :class="status === 'private' ? 'bg-warning/20 text-warning' : 'text-gray-500 hover:text-gray-300'"
                class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all">
            <span class="w-1.5 h-1.5 rounded-full" :class="status === 'private' ? 'bg-warning' : 'bg-gray-500'"></span>
            Private
        </button>
    </div>
</div>
