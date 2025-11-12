<x-layouts.app>
    <x-slot name="breadcrumbs">
        <a href="{{ route('projets.index') }}" class="text-blue-600 hover:underline">{{ $projet->nom }}</a>
        <span class="text-gray-400">/</span>
        <span class="text-gray-500">Modules</span>
    </x-slot>

    <div class="container">
        @if(session()->has('message'))
            <div class="alert-success">{{ session('message') }}</div>
        @endif

        <div class="header">
            <div class="text-xl font-semibold text-gray-800">Modules du projet <span class="text-blue-600">{{ $projet->nom }}</span></div>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('modules.create', $projet->id) }}" class="btn-primary">Créer module</a>
            @endif
        </div>

        <div class="projects-list">
            @foreach($modules as $module)
                <div class="project-card">
                    <div class="project-info">
                        <a href="{{ route('fonctionnalites.index', $module) }}" class="project-title">{{ $module->nom }}</a>
                        <p class="project-desc">{{ $module->description }}</p>
                        <p class="project-count">{{ $module->fonctionnalites_count }} fonctionnalités</p>
                    </div>
                    @if(auth()->user()->role === 'admin')
                        <div class="project-actions">
                            {{-- <a href="{{ route('projets.edit', $module->id) }}" class="btn-edit">Modifier</a> --}}
                            {{-- <button wire:click="deleteProjet({{ $module->id }})" class="btn-delete">Supprimer</button> --}}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
