@props(['type' => 'submit'])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'w-full py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-base font-semibold transition-all transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer shadow-lg shadow-indigo-600/20']) }}>
    {{ $slot }}
</button>
