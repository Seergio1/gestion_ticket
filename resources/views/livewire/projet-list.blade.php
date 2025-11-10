<x-layouts.app>
    <x-slot name="breadcrumbs">
        <a href="{{ route('projets.index') }}" class="text-blue-600">Projets</a>
    </x-slot>

<div class="max-w-5xl mx-auto p-4">
    @if(session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-2">{{ session('message') }}</div>
    @endif

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Projets</h2>
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('projets.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Créer Projet</a>
        @endif
    </div>

    <div class="bg-white shadow rounded">
        @foreach($projets as $projet)
            <div class="flex justify-between items-center p-4 border-b">
                <div>
                    <a href="{{ route('tickets.index', $projet->id) }}" class="font-semibold text-blue-600">{{ $projet->nom }}</a>
                    <p class="text-gray-500 text-sm">{{ $projet->description }}</p>
                    <p class="text-gray-400 text-xs">{{ $projet->tickets_count }} tickets</p>
                </div>
                @if(auth()->user()->role === 'admin')
                    <button wire:click="deleteProjet({{ $projet->id }})" class="text-red-500 hover:text-red-700">Supprimer</button>
                @endif
            </div>
        @endforeach
    </div>
</div>

</x-layouts.app>
