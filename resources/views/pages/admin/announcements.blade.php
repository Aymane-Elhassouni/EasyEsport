<x-layouts.app title="Announcements">

<script src="{{ asset('js/announcements.js') }}"></script>

<div x-data="announcementsData()">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-display font-black uppercase tracking-tight">
                <span class="text-primary">Announcements</span>
            </h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">{{ $tournaments->sum(fn($t) => $t->announcements->count()) }} total</p>
        </div>
        <x-ui.button @click="createOpen = true" variant="primary" size="sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Announcement
        </x-ui.button>
    </div>

    {{-- Announcements List --}}
    <div class="space-y-4">
        @forelse($tournaments->filter(fn($t) => $t->announcements->count()) as $tournament)
            @foreach($tournament->announcements as $ann)
                <div class="glass rounded-2xl border border-white/5 overflow-hidden hover:border-white/10 transition-all">
                    <div class="flex items-center justify-between px-5 py-3 border-b border-white/5">
                        <div class="flex items-center gap-3">
                            <span class="text-base">📢</span>
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest">{{ $tournament->name }}</p>
                                <p class="text-[10px] text-gray-500">{{ $ann->created_at->diffForHumans() }} · {{ $ann->author?->firstname }} {{ $ann->author?->lastname }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-widest border
                                {{ $ann->status === 'public' ? 'bg-success/10 text-success border-success/20' : 'bg-warning/10 text-warning border-warning/20' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $ann->status === 'public' ? 'bg-success' : 'bg-warning' }}"></span>
                                {{ $ann->status }}
                            </span>
                            <a href="/admin/announcements/{{ $ann->slug }}"
                               class="px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-gray-400 hover:text-white hover:border-white/20 text-xs font-bold transition-all">
                                View
                            </a>
                            <button type="button"
                                    @click="openEdit('{{ $ann->slug }}', {{ Js::from($ann->title) }}, {{ Js::from($ann->body) }}, '{{ $ann->status }}')"
                                    class="px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-gray-400 hover:text-primary hover:border-primary/30 text-xs font-bold transition-all">
                                Edit
                            </button>
                             <form method="POST" action="{{ route('admin.tournaments.announcements.destroy', $ann) }}">
                                 @csrf
                                 @method('DELETE')
                                 <button class="px-3 py-1 rounded-lg bg-danger/5 border border-danger/20 text-danger hover:bg-danger hover:text-white text-xs font-bold transition-all">
                                     Delete
                                 </button>
                             </form>
                        </div>
                    </div>
                    <div class="px-5 py-4 space-y-1.5">
                        @if($ann->banner_url)
                            <img src="{{ $ann->banner_url }}" class="w-full h-36 object-cover rounded-xl mb-3">
                        @endif
                        <h4 class="font-black uppercase tracking-tight text-sm">{{ $ann->title }}</h4>
                        <p class="text-sm text-gray-400 leading-relaxed">{{ $ann->body }}</p>
                    </div>
                </div>
            @endforeach
        @empty
            <div class="glass p-16 rounded-3xl border border-white/5 text-center opacity-40">
                <p class="text-sm font-bold uppercase tracking-widest">No announcements yet</p>
            </div>
        @endforelse
    </div>

    {{-- CREATE MODAL --}}
    <div x-show="createOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="createOpen = false"></div>
        <div class="relative w-full max-w-lg glass rounded-3xl border border-white/10 shadow-glass z-10"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between px-6 py-5 border-b border-white/5">
                <h2 class="font-display font-bold text-lg">New Announcement</h2>
                <button @click="createOpen = false" class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" :action="createAction" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Tournament</label>
                    <div x-data="{ dropOpen: false, label: 'Select a tournament' }" class="relative">
                        <button type="button" @click="dropOpen = !dropOpen"
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-left flex items-center justify-between hover:border-primary/50 transition-colors">
                            <span x-text="label" class="truncate text-gray-300"></span>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="dropOpen" @click.outside="dropOpen = false" x-cloak
                             class="absolute z-50 mt-2 w-full bg-dark-bg border border-white/10 rounded-xl overflow-hidden shadow-xl">
                            @foreach($allTournaments as $t)
                                <button type="button"
                                        @click="label = '{{ $t->name }}'; createAction = '/admin/tournaments/{{ $t->slug }}/announcements'; dropOpen = false"
                                        class="w-full px-4 py-3 text-sm text-left hover:bg-primary/10 hover:text-primary transition-colors text-gray-300">
                                    {{ $t->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Title</label>
                    <input type="text" name="title" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors" required>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Message</label>
                    <textarea name="body" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors resize-none" required></textarea>
                </div>
                @include('pages.admin.partials.visibility-toggle', ['current' => 'public'])
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Banner <span class="text-gray-500 normal-case font-normal tracking-normal">(optional)</span></label>
                    <input type="file" name="banner" accept="image/*"
                           class="w-full text-sm text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors cursor-pointer">
                </div>
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-white/5">
                    <x-ui.button type="button" variant="ghost" @click="createOpen = false">Cancel</x-ui.button>
                    <x-ui.button type="submit" variant="primary">Post</x-ui.button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div x-show="editOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="editOpen = false"></div>
        <div class="relative w-full max-w-lg glass rounded-3xl border border-white/10 shadow-glass z-10"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between px-6 py-5 border-b border-white/5">
                <h2 class="font-display font-bold text-lg">Edit Announcement</h2>
                <button @click="editOpen = false" class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form :action="'/admin/announcements/' + encodeURIComponent(editSlug)" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Title</label>
                    <input type="text" name="title" x-model="editTitle"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors" required>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Message</label>
                    <textarea name="body" rows="3" x-model="editBody"
                              class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-colors resize-none" required></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Visibility</label>
                    <input type="hidden" name="status" x-model="editStatus">
                    <div class="flex items-center gap-1 p-1 bg-white/5 border border-white/10 rounded-xl w-fit">
                        <button type="button" @click="editStatus = 'public'"
                                :class="editStatus === 'public' ? 'bg-success/20 text-success' : 'text-gray-500 hover:text-gray-300'"
                                class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all">
                            <span class="w-1.5 h-1.5 rounded-full" :class="editStatus === 'public' ? 'bg-success' : 'bg-gray-500'"></span>
                            Public
                        </button>
                        <button type="button" @click="editStatus = 'private'"
                                :class="editStatus === 'private' ? 'bg-warning/20 text-warning' : 'text-gray-500 hover:text-gray-300'"
                                class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all">
                            <span class="w-1.5 h-1.5 rounded-full" :class="editStatus === 'private' ? 'bg-warning' : 'bg-gray-500'"></span>
                            Private
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Banner <span class="text-gray-500 normal-case font-normal tracking-normal">(optional)</span></label>
                    <input type="file" name="banner" accept="image/*"
                           class="w-full text-sm text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors cursor-pointer">
                </div>
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-white/5">
                    <x-ui.button type="button" variant="ghost" @click="editOpen = false">Cancel</x-ui.button>
                    <x-ui.button type="submit" variant="primary">Save Changes</x-ui.button>
                </div>
            </form>
        </div>
    </div>

</div>
</x-layouts.app>
