<x-layouts.app>
    <x-slot name="breadcrumbs">
        <a href="{{ route('projets.index') }}" class="breadcrumb-link">Projets</a>
    </x-slot>

    <div class="container">
        @if(session()->has('message'))
            <div class="alert-success">{{ session('message') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <div class="header">
            <h2></h2>
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
                    <div class="action-container">
                        <button class="menu-btn" onclick="toggleMenu({{ $projet->id }})">
                            <img src="{{ asset('storage/icons/menu.png') }}" alt="filter-icon" class="w-4 h-4">
                        </button>
                        <div id="menu-{{ $projet->id }}" class="menu-dropdown hidden">
                            <ul>
                                <li><a href="{{ route('modules.import', $projet->id) }}">Import</a></li>
                                <li><a href="{{ route('modules.index', $projet) }}">Modules</a></li>
                                <li><a href="{{ route('projets.edit', $projet->id) }}">Modifier</a></li>
                                <li>
                                    <button 
                                        wire:click="deleteProjet({{ $projet->id }})" 
                                        onclick="if(!confirm('Voulez-vous vraiment supprimer ce projet ?')) event.stopImmediatePropagation();"
                                    >Supprimer</button>
                                </li>
                                <li>
                                    <button 
                                        wire:click="clearModules({{ $projet->id }})" 
                                        onclick="if(!confirm('Voulez-vous vraiment vider tous les modules et fonctionnalités de ce projet ?')) event.stopImmediatePropagation();"
                                    >Vider</button>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        function toggleMenu(id) {
            const menu = document.getElementById(`menu-${id}`);
            document.querySelectorAll('.menu-dropdown').forEach(m => {
                if (m !== menu) m.classList.add('hidden');
            });
            menu.classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.menu-btn') && !e.target.closest('.menu-dropdown')) {
                document.querySelectorAll('.menu-dropdown').forEach(m => m.classList.add('hidden'));
            }
        });
    </script>
</x-layouts.app>
