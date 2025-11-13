<x-layouts.app>
    <x-slot name="breadcrumbs">
        <a href="{{ route('projets.index') }}" class="text-blue-600 hover:underline">{{ $module->projet->nom }}</a>
        <span class="mx-1 text-gray-400">/</span>
        <a href="{{ route('modules.index', $module->projet) }}" class="text-blue-600 hover:underline">Modules</a>
        <span class="mx-1 text-gray-400">/</span>
        <a href="{{ route('fonctionnalites.index', $module) }}" class="text-blue-600 hover:underline">Fonctionnalités</a>
        <span class="mx-1 text-gray-400">/</span>
        <span class="text-gray-800 font-semibold">
            {{-- {{ $projet ? 'Modifier le projet' : 'Nouveau projet' }} --}}
            {{ $fonctionnalite_id ? 'Modifier la fonctionnalité' : 'Nouvelle fonctionnalité' }}
        </span>
    </x-slot>

    <div class="max-w-3xl mx-auto p-4">
        

        @if(session()->has('message'))
            <div class="bg-green-100 p-2 rounded mb-2">{{ session('message') }}</div>
        @endif

        <div class="bg-white shadow rounded p-4">
            <form wire:submit.prevent="save">
                <label class="block mt-2 font-semibold">Fonctionnalité</label>
                <input type="text" wire:model="nom" class="w-full border rounded p-2" placeholder="Nom du module">
                @error('nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <label class="block mt-2 font-semibold">Description</label>
                <textarea wire:model="description" class="w-full border rounded p-2" placeholder="Description (optionnelle)"></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
                    {{ $fonctionnalite_id ? 'Mettre à jour' : 'Créer Fonctionnalité' }}
                </button>
            </form>
        </div>

    </div>

</x-layouts.app>

