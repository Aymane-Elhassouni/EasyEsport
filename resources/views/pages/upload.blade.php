<x-layouts.app title="OCR Scanner">
    <div class="max-w-4xl mx-auto space-y-8">
        
        <!-- Header Section -->
        <div class="text-center space-y-4 mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/10 border border-primary/20 text-primary text-[10px] font-bold uppercase tracking-[0.2em] animate-fade-in">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                </span>
                Experimental Lab
            </div>
            <h1 class="text-4xl md:text-5xl font-display font-black tracking-tight dark:text-white">
                OCR <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Scanner</span>
            </h1>
            <p class="text-gray-500 dark:text-gray-400 max-w-lg mx-auto text-sm leading-relaxed">
                Upload your match screenshots to automatically extract statistics, ranks, and player data using our advanced neural vision system.
            </p>
        </div>

        <!-- Main Scanner Component -->
        <x-ocr-upload />

        <!-- Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <div class="p-6 rounded-3xl bg-dark-surface/50 dark:bg-white/5 border border-light-border dark:border-white/10 backdrop-blur-sm group hover:border-primary/30 transition-all duration-500">
                <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <h3 class="text-lg font-bold mb-2 dark:text-white">Instant Analysis</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Processing takes less than 2 seconds using our optimized edge-AI models.</p>
            </div>

            <div class="p-6 rounded-3xl bg-dark-surface/50 dark:bg-white/5 border border-light-border dark:border-white/10 backdrop-blur-sm group hover:border-secondary/30 transition-all duration-500">
                <div class="w-12 h-12 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.040L3 5.618a11.955 11.955 0 0112 2.944z" /></svg>
                </div>
                <h3 class="text-lg font-bold mb-2 dark:text-white">High Accuracy</h3>
                <p class="text-xs text-gray-500 leading-relaxed">99% detection rate for Valorant, CS2, and League of Legends scoreboards.</p>
            </div>

            <div class="p-6 rounded-3xl bg-dark-surface/50 dark:bg-white/5 border border-light-border dark:border-white/10 backdrop-blur-sm group hover:border-success/30 transition-all duration-500">
                <div class="w-12 h-12 rounded-2xl bg-success/10 flex items-center justify-center text-success mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                </div>
                <h3 class="text-lg font-bold mb-2 dark:text-white">Auto Sync</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Extracted stats are automatically synced with your global player profile.</p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Additional page-specific scripts if needed
    </script>
    @endpush
</x-layouts.app>
