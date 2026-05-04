<x-layouts.app>
    <x-slot name="title">Create Team</x-slot>

    <div class="max-w-md mx-auto mt-10">
        <h1 class="text-2xl font-bold text-white mb-6">Create Your Team</h1>

        @if ($errors->any())
            <div class="bg-red-500/20 border border-red-500 text-red-400 rounded p-3 mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('teams.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-gray-400 mb-1">Team Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded px-4 py-2 focus:outline-none focus:border-indigo-500"
                    required>
            </div>
            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded transition">
                Create Team
            </button>
        </form>
    </div>
</x-layouts.app>
