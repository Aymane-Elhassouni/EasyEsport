@props(['title' => 'Dashboard'])

<nav class="sticky top-0 z-40 bg-light-bg/80 dark:bg-dark-bg/80 backdrop-blur-xl border-b border-light-border dark:border-dark-border/30 transition-colors duration-300">
    <div class="flex items-center justify-between px-6 py-4">
        
        <div class="flex items-center gap-4">
            <!-- Mobile Toggle -->
            <button @click="$store.global.sidebarOpen = true" class="lg:hidden p-2 text-gray-500 hover:text-primary transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <h1 class="text-xl font-display font-bold">{{ $title }}</h1>
        </div>

        <div class="flex items-center gap-3 md:gap-5">
            <!-- Theme Toggle -->
            <button @click="$store.global.toggleTheme()" class="p-2 rounded-xl text-gray-400 hover:text-primary hover:bg-primary/10 transition-all">
                <svg x-show="!$store.global.darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                <svg x-show="$store.global.darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707"></path></svg>
            </button>

            <!-- Notifications -->
            <div x-data="{
                open: false,
                notifications: [],
                unread: 0,
                async load() {
                    const res = await fetch('{{ route('notifications.dropdown') }}');
                    const data = await res.json();
                    this.notifications = data.notifications;
                    this.unread = data.unread;
                }
            }" x-init="load()" class="relative">
                <button @click="open = !open" class="p-2 rounded-xl text-gray-400 hover:text-primary hover:bg-primary/10 transition-all relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span x-show="unread > 0" data-notif-badge data-count="0" class="absolute top-1.5 right-2 w-2 h-2 bg-danger rounded-full animate-pulse-soft border-2 border-light-bg dark:border-dark-bg"></span>
                </button>
                
                <div x-show="open" @click.outside="open = false" x-transition x-cloak class="absolute right-0 mt-3 w-80 glass rounded-2xl shadow-xl border border-white/10 overflow-hidden">
                    <div class="p-4 border-b border-white/5 font-bold text-sm bg-black/5 dark:bg-white/5 flex items-center justify-between">
                        <span>Notifications</span>
                        <a href="{{ route('notifications.index') }}" class="text-xs text-primary font-bold hover:underline">View all</a>
                    </div>
                    <div class="max-h-80 overflow-y-auto divide-y divide-white/5" data-notif-list>
                        <template x-if="notifications.length === 0">
                            <div class="p-4 text-xs text-gray-500 text-center">No new notifications</div>
                        </template>
                        <template x-for="n in notifications" :key="n.id">
                            <div class="flex items-start gap-3 p-4 hover:bg-white/5 transition-all">
                                <a :href="n.action_url ?? '#'" class="flex items-start gap-3 flex-1 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center text-sm flex-shrink-0" x-text="n.icon ?? '🔔'"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold" x-text="n.title"></p>
                                        <p class="text-xs opacity-50 truncate" x-text="n.message"></p>
                                        <p class="text-[10px] opacity-30 mt-1" x-text="n.time"></p>
                                    </div>
                                </a>
                                <button @click="fetch('/notifications/' + n.id, {method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(() => { notifications = notifications.filter(x => x.id !== n.id); unread = notifications.filter(x => !x.is_read).length })"
                                    class="text-white/20 hover:text-danger transition-colors flex-shrink-0 mt-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Profile Menu -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-3 pl-3 md:pl-5 border-l border-light-border dark:border-dark-border/50">
                    <div class="hidden sm:block text-right">
                        <p class="text-sm font-bold truncate max-w-[120px]">{{ trim((Auth::user()->firstname ?? '') . ' ' . (Auth::user()->lastname ?? '')) ?: (Auth::user()->name ?? 'Player') }}</p>
                        <p class="text-[10px] text-primary font-bold tracking-widest uppercase">{{ Auth::user()->role?->name ?? 'Player' }}</p>
                    </div>
                    <img src="{{ Auth::user()?->avatar_url ?? 'https://api.dicebear.com/7.x/identicon/svg?seed=guest' }}" class="w-10 h-10 rounded-xl bg-dark-bg ring-2 ring-primary/20 hover:ring-primary transition-all">
                </button>

                <div x-show="open" @click.outside="open = false" x-transition x-cloak class="absolute right-0 mt-4 w-48 glass rounded-2xl p-2 shadow-xl border border-white/10 z-[60]">
                    <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-bold hover:bg-white/5 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        My Profile
                    </a>
                    <a href="{{ route('settings') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-bold hover:bg-white/5 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Settings
                    </a>
                    <div class="h-px bg-white/5 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-xs font-bold text-danger hover:bg-danger/5 rounded-xl transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</nav>
