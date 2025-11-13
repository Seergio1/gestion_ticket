<x-layouts.app>
    <x-slot name="breadcrumbs">
        <a href="{{ route('projets.index') }}" class="text-blue-600 hover:underline">{{ $module->projet->nom }}</a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('modules.index', $module->projet_id) }}" class="text-blue-600 hover:underline">Modules</a>
        <span class="text-gray-400">/</span>
        <span class="text-gray-500">{{ $module->nom }}</span>
    </x-slot>

    <div class="container">
        @if(session()->has('message'))
            <div class="alert-success">{{ session('message') }}</div>
        @endif

        <div class="header">
            <div class="text-xl font-semibold text-gray-800">Fonctionnalités du module <span class="text-blue-600">{{ $module->nom }}</span></div>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('fonctionnalites.create', $module->id) }}" class="btn-primary">Créer fonctionnalité</a>
            @endif
        </div>

        <div class="projects-list">
            @forelse ($fonctionnalites as $fonctionnalite)
                <div class="project-card">
                    <div class="project-info">
                        <p class="">{{ $fonctionnalite->nom }}</p>
                        <p class="project-desc">{{ $fonctionnalite->description }}</p>
                    </div>
                    @if(auth()->user()->role === 'admin')
                        <div class="project-actions">
                            <a href="{{ route('fonctionnalites.edit', [$module->id, $fonctionnalite->id]) }}" class="btn-edit">Modifier</a>
                            <button wire:click="deleteFonctionnalite({{ $fonctionnalite->id }})" class="btn-delete">Supprimer</button>
                        </div>
                    @endif
                </div>
            @empty
                <div id="empty-container" class="col-span-full p-6 text-center text-gray-500 italic">
                    Aucune fonctionnalité enregistré pour ce module.
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
