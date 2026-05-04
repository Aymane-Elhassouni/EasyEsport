@props(['data'])

<div class="glass border border-white/10 rounded-3xl p-8 mt-8">
    <h2 class="text-xl font-display font-bold mb-6 flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </div>
        Edit Profile
    </h2>

    <form method="POST" action="{{ route('profile.update') }}" id="profile-form" class="space-y-6">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-xs font-bold uppercase tracking-widest opacity-60 mb-2">Bio</label>
            <textarea name="bio" rows="4" maxlength="1000"
                      placeholder="Tell the community about yourself..."
                      class="w-full bg-dark-surface border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all resize-none placeholder-gray-500">{{ old('bio', Auth::user()->profile->bio ?? '') }}</textarea>
            <p class="text-[10px] opacity-40 mt-1 text-right">max 1000 characters</p>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit"
                    class="px-8 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:shadow-neon-primary transition-all duration-300 hover:scale-105 active:scale-95">
                Save Changes
            </button>
        </div>
    </form>
</div>

{{-- ── SweetAlert Notifications ─────────────────────────────────────── --}}
@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        background: '#1e293b',
        color: '#f1f5f9',
        iconColor: '#22c55e',
    });
</script>
@endif

@if($errors->any())
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: '<ul class="text-left text-sm space-y-1">' +
            @foreach($errors->all() as $error)
            '<li>• {{ $error }}</li>' +
            @endforeach
            '</ul>',
        background: '#1e293b',
        color: '#f1f5f9',
        iconColor: '#ef4444',
        confirmButtonColor: '#6366f1',
        confirmButtonText: 'Fix it',
    });
</script>
@endif
