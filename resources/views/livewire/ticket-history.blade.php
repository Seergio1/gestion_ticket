<x-layouts.app>
    <x-slot name="breadcrumbs">
        <a href="{{ route('projets.index') }}" class="text-blue-600 hover:underline">Projets</a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('tickets.index', $ticket->projet->id) }}" class="text-blue-600 hover:underline">Tickets</a>
        <span class="text-gray-400">/</span>
        <span class="text-gray-500">Historique</span>
    </x-slot>

    <div class="max-w-6xl mx-auto p-4">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-2">
            <h2 class="text-xl font-semibold text-gray-800">
                Historique du ticket <span class="text-blue-600">{{ $ticket->id }} - {{ $ticket->projet->nom }} ({{ $history->total() }})</span>
            </h2>
        </div>

        <div class="ticket-table-wrapper">
        <table class="ticket-table">
            <thead>
                <tr>
                    {{-- <th>ID / Module</th> --}}
                    <th>Fonctionnalité</th>
                    <th>Description</th>
                    <th>Commentaire</th>
                    
                    <th>État</th>
                    <th>Status</th>
                    <th>Fichiers</th>
                    <th>Modifié par</th>
                    <th>Modifié le</th>
                </tr>
            </thead>

            <tbody>
            @forelse($history as $item)
                <tr>
                    {{-- <td>
                        <div class="font-medium">
                            {{ $item->ticket_id }} - {{ $item->module ? $item->module->nom : '—'}}
                        </div>
                    </td> --}}

                    <td class="wrap-cell">
                        {{ $item->fonctionnalite ? $item->fonctionnalite->nom : '—' }}
                    </td>

                    <td class="wrap-cell">
                        {{ $item->fonctionnalite ? $item->fonctionnalite->description : '—' }}
                    </td>

                    <td class="wrap-cell">
                        {{ $item->commentaire ? $item->commentaire : '—' }}
                    </td>

                    <td>
                        <div class="badge-wrapper">
                            <span class="badge {{ $item->etat === 'en cours' ? 'badge-warning' : 'badge-success' }}">
                                {{ ucfirst($item->etat) }}
                            </span>
                        </div>
                    </td>

                    <td>
                        <div class="badge-wrapper">
                            <span class="badge 
                                {{ $item->status === 'ok' ? 'badge-success' :
                                ($item->status === 'à améliorer' ? 'badge-warning' : 'badge-danger') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                    </td>


                    <td style="text-align: center;">
                        @if($item->fichiers && count($item->fichiers))
                            {{-- {{ count($item->fichiers) }} --}}
                            <ul class="flex flex-col gap-1">
                                @foreach($item->fichiers as $file)
                                    <li>
                                        <a href="{{ asset('storage/'.$file) }}" target="_blank"
                                        class="text-blue-600 hover:underline text-sm">
                                            Fichier_{{ $loop->iteration }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            —
                        @endif
                    </td>

                    <td>
                        <span class="text-gray-700">{{ $item->user->name }}</span>
                    </td>

                    <td>
                        {{ $item->created_at->format('d/m/Y H:i') }}
                    </td>


                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-500 italic">
                        Aucun historique enregistré pour ce ticket.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{-- pagination --}}
        <div class="mt-4 mb-4 flex justify-center">
            {{ $history->links() }}
        </div>
    </div>
    </div>
</x-layouts.app>