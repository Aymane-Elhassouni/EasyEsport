@props(['label', 'name', 'type' => 'text', 'value' => ''])

<div class="mb-5">
    @if($label)
        <label for="{{ $name }}" class="block mb-2 text-sm font-medium text-slate-400">
            {{ $label }}
        </label>
    @endif
    
    <input 
        id="{{ $name }}" 
        type="{{ $type }}" 
        name="{{ $name }}" 
        value="{{ old($name, $value) }}" 
        {{ $attributes->merge(['class' => 'w-full px-4 py-3 bg-slate-900/50 border border-white/10 rounded-xl text-white text-base transition-all focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20']) }}
    >

    @error($name)
        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
    @enderror
</div>
