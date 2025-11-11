<x-layouts.app>
    <x-slot name="breadcrumbs">
        <a href="{{ route('projets.index') }}" class="breadcrumb-link">Projets</a>
    </x-slot>

    <div class="container">
        @if(session()->has('message'))
            <div class="alert-success">{{ session('message') }}</div>
        @endif

        <div class="header">
            <h2>Projets</h2>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('projets.create') }}" class="btn-primary">Créer Projet</a>
            @endif
        </div>

        <div class="projects-list">
            @foreach($projets as $projet)
                <div class="project-card">
                    <div class="project-info">
                        <a href="{{ route('tickets.index', $projet->id) }}" class="project-title">{{ $projet->nom }}</a>
                        <p class="project-desc">{{ $projet->description }}</p>
                        <p class="project-count">{{ $projet->tickets_count }} tickets</p>
                    </div>
                    @if(auth()->user()->role === 'admin')
                        <div class="project-actions">
                            <a href="{{ route('projets.edit', $projet->id) }}" class="btn-edit">Modifier</a>
                            <button wire:click="deleteProjet({{ $projet->id }})" class="btn-delete">Supprimer</button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
