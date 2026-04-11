<x-layout>
    <div class="glass-card p-10 w-full mx-auto max-w-[600px]">
        <h1 class="font-heading text-3xl font-bold mb-2">Dashboard</h1>
        <p class="text-slate-400 mb-8">Welcome back, {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}!</p>

        <div class="mt-8 p-6 bg-white/5 rounded-2xl text-left border border-white/5 mb-8">
            <h3 class="mt-0 text-sky-500 font-bold mb-4">User Info</h3>
            <p class="my-2 text-sm"><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p class="my-2 text-sm"><strong>Role ID:</strong> {{ Auth::user()->role_id }}</p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-button class="!bg-red-500/10 !text-red-500 border border-red-500/20 hover:!bg-red-500/20 shadow-none">
                Log Out
            </x-button>
        </form>
    </div>
</x-layout>
