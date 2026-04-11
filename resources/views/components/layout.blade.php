<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EasyEsport') }}</title>

    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="p-6 px-8 flex justify-between items-center backdrop-blur-md border-b border-white/5">
        <a href="/" class="font-heading text-2xl font-bold bg-gradient-to-right from-indigo-500 to-sky-500 bg-clip-text text-transparent">
            EasyEsport
        </a>
    </nav>

    <main class="flex-1 flex items-center justify-center p-8">
        {{ $slot }}
    </main>
</body>
</html>
