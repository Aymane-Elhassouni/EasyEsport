<x-layout>
    <div class="glass-card p-10 w-full max-auto max-w-[450px]">
        <h1 class="font-heading text-3xl font-bold text-center mb-2">Create Account</h1>
        <p class="text-slate-400 text-center mb-8">Join the elite e-sports community</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="flex gap-4">
                <x-input class="flex-1" label="First Name" name="firstname" required autofocus />
                <x-input class="flex-1" label="Last Name" name="lastname" required />
            </div>

            <x-input label="Email Address" name="email" type="email" required />
            <x-input label="Password" name="password" type="password" required />
            <x-input label="Confirm Password" name="password_confirmation" type="password" required />

            <x-button>Create Account</x-button>

            <p class="text-center mt-6 text-sm text-slate-400">
                Already have an account? <a href="{{ route('login') }}" class="text-sky-500 font-semibold hover:underline">Sign In</a>
            </p>
        </form>
    </div>
</x-layout>
