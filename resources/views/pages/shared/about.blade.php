<x-layouts.app title="About EasyEsport">

    {{-- Hero --}}
    <div class="text-center max-w-2xl mx-auto mb-12 space-y-4">
        <span class="inline-block px-4 py-1.5 rounded-full bg-secondary/10 text-secondary text-xs font-black uppercase tracking-widest border border-secondary/20">
            Our Story
        </span>
        <h1 class="text-4xl font-display font-black uppercase tracking-tight">
            About <span class="text-primary">EasyEsport</span>
        </h1>
        <p class="text-gray-400 leading-relaxed">
            EasyEsport is a next-generation competitive gaming platform built for players who take their game seriously.
            From tournament management to AI-powered result validation, we handle the infrastructure so you can focus on winning.
        </p>
    </div>

    {{-- Values --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        @foreach([
            ['title' => 'Fair Play',       'desc' => 'OCR-powered result validation ensures every match outcome is accurate and tamper-proof.'],
            ['title' => 'Community First', 'desc' => 'Built around teams, players, and the competitive spirit that drives eSport forward.'],
            ['title' => 'Transparency',    'desc' => 'Open brackets, public leaderboards, and real-time stats for every tournament.'],
            ['title' => 'Performance',     'desc' => 'Optimized for speed and reliability so nothing gets in the way of your game.'],
        ] as $value)
            <x-ui.card>
                <h3 class="font-display font-bold text-lg text-primary mb-2">{{ $value['title'] }}</h3>
                <p class="text-sm text-gray-400 leading-relaxed">{{ $value['desc'] }}</p>
            </x-ui.card>
        @endforeach
    </div>

    {{-- CTA --}}
    <div class="text-center">
        <x-ui.button href="{{ route('tournaments') }}" variant="primary" size="lg">
            Start Competing
        </x-ui.button>
    </div>

</x-layouts.app>
