<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data
      :class="{ 'dark': $store.global.darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth<meta name="user-id" content="{{ auth()->id() }}">@endauth
    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name', 'EasyEsport') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light-bg dark:bg-dark-bg text-dark-surface dark:text-light-bg transition-colors duration-500 overflow-x-hidden font-sans">
    
    <!-- Background Patterns -->
    <div class="fixed inset-0 z-[-1] pointer-events-none opacity-20 dark:opacity-40 bg-grid"></div>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @auth
            <x-sidebar />
        @endauth

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300">
            <!-- Top Navbar -->
            <x-navbar :title="$title ?? 'Dashboard'" />

            <!-- Content Area -->
            <main class="flex-1 p-3 md:p-5 pt-20">
                <div class="w-full">
                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            <footer class="p-8 border-t border-light-border dark:border-dark-border/30 text-center text-xs opacity-50 uppercase tracking-widest">
                &copy; {{ date('Y') }} EasyEsport &bull; Design for Champions
            </footer>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: '{{ session('success') }}', showConfirmButton: false, timer: 3500, timerProgressBar: true, background: '#1e293b', color: '#f1f5f9', iconColor: '#22c55e' }));
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: '{{ session('error') }}', showConfirmButton: false, timer: 3500, timerProgressBar: true, background: '#1e293b', color: '#f1f5f9', iconColor: '#ef4444' }));
    </script>
    @endif

    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', () => Swal.fire({ icon: 'error', title: 'Validation Error', html: '<ul class="text-left text-sm space-y-1">{!! implode('', array_map(fn($e) => '<li>• ' . e($e) . '</li>', $errors->all())) !!}</ul>', background: '#1e293b', color: '#f1f5f9', iconColor: '#ef4444', confirmButtonColor: '#6366f1', confirmButtonText: 'Fix it' }));
    </script>
    @endif
    @stack('scripts')
</body>
</html>
