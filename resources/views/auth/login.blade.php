<x-layout>
    <div class="glass-card p-10 w-full max-auto max-w-[450px]">
        <h1 class="font-heading text-3xl font-bold text-center mb-2">Welcome Back</h1>
        <p class="text-slate-400 text-center mb-8">Sign in to your EasyEsport account</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <x-input label="Email Address" name="email" type="email" required autofocus />
            <x-input label="Password" name="password" type="password" required />

            <x-button>Sign In</x-button>

            <p class="text-center mt-6 text-sm text-slate-400">
                Don't have an account? <a href="{{ route('register') }}" class="text-sky-500 font-semibold hover:underline">Create One</a>
            </p>
        </form>
    </div>
</x-layout>
