<x-layouts.app>
    <x-slot name="title">Settings</x-slot>

    <div class="max-w-5xl mx-auto space-y-8">
        <x-profile-info :data="$data" />
        <x-edit-profile-form :data="$data" />
    </div>
</x-layouts.app>
