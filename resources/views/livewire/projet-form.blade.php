<x-layouts.app>
    <x-slot name="breadcrumbs">
        <a href="{{ route('projets.index') }}" class="text-blue-600 hover:underline">Projets</a>
        <span class="mx-1 text-gray-400">/</span>
        <span class="text-gray-800 font-semibold">Nouveau projet</span>
    </x-slot>

    <div class="max-w-3xl mx-auto p-4">
        @if(session()->has('message'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-2">
                {{ session('message') }}
            </div>
        @endif
        <div class="bg-white shadow rounded p-4">
        <form wire:submit.prevent="save">
            <label class="block mt-2 font-semibold">Nom du projet</label>
            <input type="text" wire:model="nom" class="w-full border rounded p-2" placeholder="Nom du projet">
            @error('nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <label class="block mt-2 font-semibold">Description</label>
            <textarea wire:model="description" class="w-full border rounded p-2" placeholder="Description (optionnelle)"></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
                Créer Projet
            </button>
        </form>
    </div>
    </div>
</x-layouts.app>
