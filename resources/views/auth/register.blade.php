<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Join the Elite - EasyEsport</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-dark-bg text-white overflow-hidden font-sans selection:bg-primary">

    <div class="flex min-h-full">
        
        <!-- Left: Branding & Visuals -->
        <div class="hidden lg:flex lg:flex-1 relative overflow-hidden bg-[#0b1120]">
            <div class="absolute inset-0 bg-grid opacity-20"></div>
            <div class="absolute top-[-10%] right-[-10%] w-[80%] h-[80%] bg-secondary/20 blur-[150px] rounded-full"></div>
            
            <div class="relative z-10 flex flex-col justify-center px-16 space-y-8">
                <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center shadow-neon-secondary">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                
                <div class="space-y-4">
                    <h1 class="text-6xl font-display font-black leading-none uppercase tracking-tight">Recruiting the <span class="text-secondary text-glow-secondary">Next Generation</span> of Pros</h1>
                    <p class="text-xl text-gray-400 max-w-lg leading-relaxed">Create your player profile, find your dream team, and participate in tournaments verified by our AI.</p>
                </div>

                <div class="grid grid-cols-2 gap-8 pt-8">
                    <div class="glass p-6 rounded-3xl border-white/5">
                        <p class="text-3xl font-black text-primary">64ms</p>
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-widest mt-1">Average response</p>
                    </div>
                     <div class="glass p-6 rounded-3xl border-white/5">
                        <p class="text-3xl font-black text-secondary">99.9%</p>
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-widest mt-1">OCR Accuracy</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Register Form (Scrollable) -->
        <div class="flex-1 flex flex-col justify-center px-6 lg:px-24 bg-dark-bg relative overflow-y-auto py-12">
            
            <div class="mx-auto w-full max-w-md space-y-8">
                <div class="text-center lg:text-left space-y-2">
                    <h2 class="text-3xl font-display font-black tracking-tight">Create your Profile</h2>
                    <p class="text-gray-500 font-medium">Already a member? <a href="{{ route('login') }}" class="text-primary font-bold hover:underline transition-all">Sign in here</a></p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5 focus-within:translate-x-1 transition-transform">
                            <label class="text-xs font-bold uppercase tracking-widest text-gray-500 px-1">First Name</label>
                            <input type="text" name="firstname" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-medium focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        </div>
                        <div class="space-y-1.5 focus-within:translate-x-1 transition-transform">
                            <label class="text-xs font-bold uppercase tracking-widest text-gray-500 px-1">Last Name</label>
                            <input type="text" name="lastname" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-medium focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        </div>
                    </div>

                    <div class="space-y-1.5 focus-within:translate-x-1 transition-transform">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-500 px-1">Email Address</label>
                        <input type="email" name="email" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-medium focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all" placeholder="name@domain.com">
                    </div>

                    <div class="space-y-1.5 focus-within:translate-x-1 transition-transform">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-500 px-1">Password</label>
                        <input type="password" name="password" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-medium focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all" placeholder="At least 8 characters">
                    </div>

                     <div class="space-y-1.5 focus-within:translate-x-1 transition-transform">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-500 px-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-medium focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    </div>

                    <div class="flex items-center gap-3 px-1">
                        <input type="checkbox" id="terms" required class="w-5 h-5 rounded-lg bg-white/5 border-white/10 text-primary focus:ring-primary focus:ring-offset-dark-bg cursor-pointer">
                        <label for="terms" class="text-xs font-medium text-gray-500 cursor-pointer hover:text-gray-300">I agree to the <span class="text-primary font-bold">Player Code of Conduct</span></label>
                    </div>

                    <button type="submit" class="w-full btn-secondary py-4 text-sm font-bold shadow-neon-secondary hover:shadow-secondary-glow tracking-[0.2em] transform hover:scale-[1.02]">
                        CREATE ACCOUNT
                    </button>
                    
                    <button type="button" class="w-full glass border-white/10 py-3 rounded-2xl flex items-center justify-center gap-3 hover:bg-white/5 transition-all text-xs font-bold">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" class="w-5 h-5">
                        SIGN UP WITH DISCORD
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
