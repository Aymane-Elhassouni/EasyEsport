<aside x-cloak
       :class="$store.global.sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed inset-y-0 left-0 w-48 xl:w-56 z-50 transition-all duration-500 transform lg:static lg:block bg-dark-bg/50 backdrop-blur-xl border-r border-light-border dark:border-dark-border/20">
    <div class="h-full flex flex-col p-6">
        
        <!-- Brand Logo -->
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center shadow-neon-primary rotate-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <span class="text-xl font-display font-black tracking-tighter text-dark-surface dark:text-white uppercase">
                Easy<span class="text-primary tracking-normal font-semibold italic">Esport</span>
            </span>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 space-y-2 overflow-y-auto custom-scrollbar pr-1">
            <div class="pb-2 px-4">
               <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Main Menu</p>
            </div>
            @php
                $dashboardRoute = Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin') 
                    ? route('admin.dashboard') 
                    : route('player.dashboard');
            @endphp
            <x-sidebar-link icon="layout" label="Dashboard" route="{{ $dashboardRoute }}" :active="request()->routeIs('admin.dashboard') || request()->routeIs('player.dashboard')" />
            <x-sidebar-link icon="trophy" label="Tournaments" route="{{ route('tournaments') }}" :active="request()->routeIs('tournaments*')" />
            <x-sidebar-link icon="users" label="Teams" route="{{ route('teams') }}" :active="request()->routeIs('teams*')" />
            
            <div class="pt-6 pb-2 px-4">
               <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tools & Labs</p>
            </div>
            <x-sidebar-link icon="camera" label="OCR Scanner" route="{{ route('upload') }}" :active="request()->routeIs('upload')" />
            
            <div class="pt-6 pb-2 px-4">
               <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Account</p>
            </div>
            <x-sidebar-link icon="trophy" label="My Profile" route="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')" />
            <x-sidebar-link icon="settings" label="Settings" route="{{ route('settings') }}" :active="request()->routeIs('settings*')" />
            
            <div class="pt-6 pb-2 px-4">
               <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Session</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl border border-transparent transition-all text-xs font-black uppercase tracking-widest text-left text-danger hover:bg-danger/5">
                    <span class="p-2 bg-danger/10 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    </span>
                    Logout
                </button>
            </form>
        </nav>

        <!-- Bottom Banner -->
        <div class="mt-6 pt-6 border-t border-white/10">
            <div class="bg-gradient-to-br from-primary/10 to-transparent border border-primary/20 rounded-2xl p-4 group cursor-pointer relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-primary/20 blur-2xl group-hover:bg-primary/40 transition-all"></div>
                <p class="text-[10px] font-bold text-primary uppercase mb-1">Go Premium</p>
                <p class="text-[11px] font-medium opacity-70 leading-relaxed">Unlock advanced team analytics & OCR logs.</p>
            </div>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div x-show="$store.global.sidebarOpen" 
         @click="$store.global.sidebarOpen = false"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm lg:hidden z-[-1] transition-opacity duration-300"></div>
</aside>
