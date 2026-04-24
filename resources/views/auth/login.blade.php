<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - EasyEsport Premium</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-dark-bg text-white overflow-hidden font-sans selection:bg-primary">

    <div class="flex min-h-full">
        
        <!-- Left: Branding & Visuals -->
        <div class="hidden lg:flex lg:flex-1 relative overflow-hidden bg-[#0b1120]">
            <!-- Decorative Elements -->
            <div class="absolute inset-0 bg-grid opacity-20"></div>
            <div class="absolute top-[-10%] right-[-10%] w-[80%] h-[80%] bg-primary/20 blur-[150px] rounded-full"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-[60%] h-[60%] bg-secondary/10 blur-[120px] rounded-full"></div>
            
            <div class="relative z-10 flex flex-col justify-center px-16 space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center shadow-neon-primary">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-3xl font-display font-extrabold tracking-tighter">EASY<span class="text-primary tracking-normal font-medium">ESPORT</span></span>
                </div>
                
                <div class="space-y-4">
                    <h1 class="text-6xl font-display font-black leading-none uppercase tracking-tight">The Future of<br/><span class="text-primary text-glow-primary">Competitive</span> Gaming</h1>
                    <p class="text-xl text-gray-400 max-w-lg leading-relaxed">Join the most advanced esport management platform. Automate matches, track stats, and win tournaments with OCR technology.</p>
                </div>

                <div class="flex items-center gap-6 pt-8">
                    <div class="flex -space-x-4">
                        @foreach(range(1, 4) as $i)
                            <img class="w-12 h-12 rounded-2xl ring-4 ring-[#0b1120]" src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $i }}" alt="user">
                        @endforeach
                    </div>
                    <div>
                        <p class="font-bold">Join 10,000+ players</p>
                        <p class="text-xs text-gray-500">Active in Valorant, LOL, and CS2</p>
                    </div>
                </div>
            </div>

            <!-- Animated Particles (Simulated) -->
            <div class="absolute bottom-10 right-10 flex flex-col gap-4 animate-float opacity-30">
                <div class="w-32 h-32 glass rounded-3xl border-primary/20 p-6 flex flex-col justify-between">
                    <div class="w-8 h-8 rounded-lg bg-primary/20"></div>
                    <div class="font-black text-2xl text-primary">#1</div>
                </div>
            </div>
        </div>

        <!-- Right: Login Form -->
        <div class="flex-1 flex flex-col justify-center px-6 lg:px-24 bg-dark-bg relative">
            
            <div class="mx-auto w-full max-w-md space-y-10">
                <div class="text-center lg:text-left space-y-2">
                    <h2 class="text-3xl font-display font-black tracking-tight">Welcome Back</h2>
                    <p class="text-gray-500 font-medium">Don't have an account? <a href="{{ route('register') }}" class="text-primary font-bold hover:underline transition-all">Sign up for free</a></p>
                </div>

                @if($errors->any())
                    <div class="bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl text-sm font-medium">
                        {{ $errors->first('email') ?? $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div class="space-y-1.5 focus-within:translate-x-1 transition-transform">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-500 px-1">Email Address</label>
                        <div class="relative group">
                            <input type="email" name="email" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-medium focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all placeholder:opacity-30" placeholder="alex@example.com">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-0 group-focus-within:opacity-100 transition-opacity">
                                <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5 focus-within:translate-x-1 transition-transform">
                        <div class="flex justify-between px-1">
                            <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Password</label>
                            <a href="#" class="text-[10px] font-bold text-gray-600 hover:text-primary transition-all">Forgot password?</a>
                        </div>
                        <input type="password" name="password" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-medium focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all placeholder:opacity-30" placeholder="••••••••">
                    </div>

                    <div class="flex items-center gap-3 px-1">
                        <input type="checkbox" id="remember" class="w-5 h-5 rounded-lg bg-white/5 border-white/10 text-primary focus:ring-primary focus:ring-offset-dark-bg cursor-pointer">
                        <label for="remember" class="text-xs font-medium text-gray-500 cursor-pointer hover:text-gray-300">Keep me logged in for 30 days</label>
                    </div>

                    <button type="submit" class="w-full btn-primary py-4 text-sm font-bold shadow-neon-primary hover:shadow-primary-glow tracking-[0.2em] transform hover:scale-[1.02]">
                        AUTHORIZE ACCESS
                    </button>
                    
                    <div class="relative py-4">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-white/5"></div></div>
                        <div class="relative flex justify-center text-xs uppercase"><span class="bg-dark-bg px-4 text-gray-600 font-bold tracking-widest">Or connect with</span></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" class="glass border-white/10 py-3 rounded-2xl flex items-center justify-center gap-3 hover:bg-white/5 transition-all group">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" class="w-5 h-5 group-hover:scale-110 transition-transform">
                            <span class="text-xs font-bold">Google</span>
                        </button>
                        <button type="button" class="glass border-white/10 py-3 rounded-2xl flex items-center justify-center gap-3 hover:bg-white/5 transition-all group">
                             <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" class="w-5 h-5 group-hover:scale-110 transition-transform">
                            <span class="text-xs font-bold">Discord</span>
                        </button>
                    </div>
                </form>

                <p class="text-[10px] text-center text-gray-600">
                    By accessing EasyEsport, you agree to our <a href="#" class="underline">Terms of Service</a> and <a href="#" class="underline">Privacy Policy</a>.
                </p>
            </div>
        </div>
    </div>

</body>
</html>
