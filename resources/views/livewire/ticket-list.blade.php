<x-layouts.app>
    <x-slot name="breadcrumbs">
        <a href="{{ route('projets.index') }}" class="text-blue-600 hover:underline">Projets</a>
        <span class="text-gray-400">/</span>
        <span class="text-gray-500">{{ $projet->nom }}</span>
    </x-slot>

<div class="max-w-6xl mx-auto p-4">
        @if(session()->has('message'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4 shadow-sm">
                {{ session('message') }}
            </div>
        @endif

        {{-- === EN-TÊTE === --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-2">
            {{-- <h2 class="text-xl font-semibold text-gray-800">
                Tickets pour <span class="text-blue-600">{{ $projet->nom }} ({{ count($tickets) }})</span>
            </h2> --}}

            <h2 class="text-xl font-semibold text-gray-800">
                Tickets pour <span class="text-blue-600">{{ $projet->nom }} ({{ $tickets->total() }})</span>
            </h2>

            <div class="flex flex-wrap sm:flex-row items-center gap-2">
                <button id="toggleFilters">
                    <img src="{{ asset('storage/icons/filtre.svg') }}" alt="filter-icon" class="w-4 h-4">
                </button>
                
                 <a href="{{ route('tickets.create', $projet->id) }}" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">
                        Création
                </a>

                @if(auth()->user()->role === 'admin')
                    <button id="ExportButton" wire:click="exportExcel"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Excel
                </button>
                @endif

            </div>
        </div>

    <div id="filtersPanel" class="filters-panel hidden">
        <div class="filters-grid">
            <div class="filter-item">
                <label>Module</label>
                <select wire:model="module_id" class="filter-input">
                    <option value="">Tous</option>
                    @foreach($modules as $m)
                        <option value="{{ $m->id }}">{{ $m->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label>Fonctionnalité</label>
                <input
                    type="text"
                    wire:model.debounce.700ms="searchFonctionnalite"
                    class="filter-input"
                />
            </div>
            
            
            <div class="filter-item">
                <label>État</label>
                <select wire:model="etat" class="filter-input">
                    <option value="">Tous</option>
                    <option value="en cours">En cours</option>
                    <option value="terminé">Terminé</option>
                </select>
            </div>

            <div class="filter-item">
                <label>Status</label>
                <select wire:model="status" class="filter-input">
                    <option value="">Tous</option>
                    <option value="ok">OK</option>
                    <option value="à améliorer">À améliorer</option>
                    <option value="erreur">Erreur</option>
                </select>
            </div>

            <div class="filter-item">
                <label>Date début</label>
                <input type="date" wire:model="dateDebut" class="filter-input">
            </div>

            <div class="filter-item">
                <label>Date fin</label>
                <input type="date" wire:model="dateFin" class="filter-input">
            </div>

            <div class="filter-item last">
                <button wire:click="resetFilters"
                    class="reset-btn" >
                    Réinitialiser
                </button>
            </div>
        </div>
    </div>


        {{-- <div class="flex flex-wrap gap-4">
            @forelse($tickets as $ticket)
                <div class="ticket-card">
                    <div class="header-ticket">
                        <div class="module-content">
                            {{ $ticket->id }} - {{ $ticket->module ? $ticket->module->nom : '—' }}
                        </div>
                        <div class="badge-container">
                            <span class="badge {{ $ticket->etat === 'en cours' ? 'badge-warning' : 'badge-success' }}">
                                {{ ucfirst($ticket->etat) }}
                            </span>
                            <span class="badge 
                                {{ $ticket->status === 'ok' ? 'badge-success' : 
                                   ($ticket->status === 'à améliorer' ? 'badge-warning' : 'badge-danger') }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="created-by">
                        Créé par <span class="font-medium">{{ $ticket->creator->name }}</span>
                    </div>

                    <div class="ticket-body-container">

                        <div class="scenario-container"> 
                            <div>Fonctionnalité</div> 
                            <div class="truncate">{{ $ticket->fonctionnalite ? $ticket->fonctionnalite->nom : '—' }}</div>
                        </div>

                        <div class="commentaire-container">
                                <div>Description</div>
                                <div class="truncate">{{ $ticket->fonctionnalite ? $ticket->fonctionnalite->description : '—' }}</div>
                        </div>

                        @if($ticket->commentaire)
                            <div class="commentaire-container">
                                <div>Commentaire</div>
                                <div class="truncate">{{ $ticket->commentaire }}</div>
                            </div>
                        @endif

                        @if($ticket->fichiers && count($ticket->fichiers) > 0)
                            <div class="attachments-container mt-2">
                                <div class="text-xs tracking-wide text-gray-400 mb-1">Fichiers attachés</div>
                                <ul class="flex flex-col gap-1">
                                    @foreach($ticket->fichiers as $file)
                                        <li>
                                            <a href="{{ asset('storage/'.$file) }}" target="_blank" 
                                               class="text-blue-600 hover:underline text-sm truncate block">
                                                {{ basename($file) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="action-container">
                        <button class="menu-btn" onclick="toggleMenu({{ $ticket->id }})">
                            <img src="{{ asset('storage/icons/menu.png') }}" alt="filter-icon" class="w-4 h-4">
                        </button>

                        <div id="menu-{{ $ticket->id }}" class="menu-dropdown hidden">
                            <ul>
                                <li>
                                    <a href="{{ route('tickets.edit', ['projet' => $projet->id, 'ticket' => $ticket->id]) }}">
                                        Modifier
                                    </a>
                                </li>
                                @if (auth()->user()->role === 'admin')
                                    <li>
                                        <button wire:click="deleteTicket({{ $ticket->id }})"
                                                onclick="confirm('Voulez-vous vraiment supprimer ce ticket ?') || event.stopImmediatePropagation()"
                                                class="text-red-600 hover:text-red-800 w-full text-left">
                                            Supprimer
                                        </button>
                                    </li>
                                @endif
                                
                            </ul>
                        </div>
                    </div>
                    
                </div>
            @empty
                <div class="col-span-full p-6 text-center text-gray-500 italic">
                    Aucun ticket enregistré pour ce projet.
                </div>
            @endforelse
        </div> --}}

    <div class="ticket-table-wrapper">
        <table class="ticket-table">
            <thead>
                <tr>
                    <th>ID / Module</th>
                    <th>Fonctionnalité</th>
                    <th>Description</th>
                    <th>Créé par</th>
                    <th>État</th>
                    <th>Status</th>
                    <th>Fichiers</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
            @forelse($tickets as $ticket)
                <tr>
                    <td>
                        <div class="font-medium">
                            {{ $ticket->id }} - {{ $ticket->module ? $ticket->module->nom : '—' }}
                        </div>
                    </td>

                    <td class="wrap-cell">
                        {{ $ticket->fonctionnalite ? $ticket->fonctionnalite->nom : '—' }}
                    </td>

                    <td class="wrap-cell">
                        {{ $ticket->fonctionnalite ? $ticket->fonctionnalite->description : '—' }}
                    </td>


                    <td>
                        <span class="text-gray-700">{{ $ticket->creator->name }}</span>
                    </td>

                    <td>
                        <div class="badge-wrapper">
                            <span class="badge {{ $ticket->etat === 'en cours' ? 'badge-warning' : 'badge-success' }}">
                                {{ ucfirst($ticket->etat) }}
                            </span>
                        </div>
                    </td>

                    <td>
                        <div class="badge-wrapper">
                            <span class="badge 
                                {{ $ticket->status === 'ok' ? 'badge-success' :
                                ($ticket->status === 'à améliorer' ? 'badge-warning' : 'badge-danger') }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                    </td>


                    <td style="text-align: center;">
                        @if($ticket->fichiers && count($ticket->fichiers))
                            {{ count($ticket->fichiers) }}
                            {{-- <ul class="flex flex-col gap-1">
                                @foreach($ticket->fichiers as $file)
                                    <li>
                                        <a href="{{ asset('storage/'.$file) }}" target="_blank"
                                        class="text-blue-600 hover:underline text-sm">
                                            Fichier_{{ $loop->iteration }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul> --}}
                        @else
                            —
                        @endif
                    </td>

                    <td class="actions-cell">
                        <a href="{{ route('tickets.edit', ['projet' => $projet->id, 'ticket' => $ticket->id]) }}"
                        class="action-btn edit"
                        title="Modifier">
                            <img src="{{ asset('storage/icons/edit.svg') }}" alt="modifier">
                        </a>

                        @if(auth()->user()->role === 'admin')
                            <button wire:click="deleteTicket({{ $ticket->id }})"
                                    onclick="confirm('Voulez-vous vraiment supprimer ce ticket ?') || event.stopImmediatePropagation()"
                                    class="action-btn delete"
                                    title="Supprimer">
                                <img src="{{ asset('storage/icons/trash.svg') }}" alt="supprimer">
                            </button>

                            <a href="{{ route('tickets.history', ['projet' => $projet->id, 'ticket' => $ticket->id]) }}"
                            class="action-btn list"
                            title="Liste">
                                <img src="{{ asset('storage/icons/history.png') }}" alt="liste">
                                @if($ticket->histories_count > 0)
                                    <span class="history-badge">
                                        {{ $ticket->histories_count }}
                                    </span>
                                @endif
                            </a>
                        @endif
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-500 italic">
                        Aucun ticket enregistré pour ce projet.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{-- pagination --}}
        <div class="mt-4 mb-4 flex justify-center">
            {{ $tickets->links() }}
        </div>
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

        document.getElementById('toggleFilters').addEventListener('click', () => {
            const panel = document.getElementById('filtersPanel');
            panel.classList.toggle('hidden');
        });
    </script>
    
</x-layouts.app>
