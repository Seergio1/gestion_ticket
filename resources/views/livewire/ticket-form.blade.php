<x-layouts.app>
    <x-slot name="breadcrumbs">
        <a href="{{ route('projets.index') }}" class="text-blue-600 hover:underline">Projets</a>
        <span class="mx-1 text-gray-400">/</span>

        <a href="{{ route('tickets.index', $projet->id) }}" class="text-blue-600 hover:underline">
            {{ $projet->nom }}
        </a>
        <span class="mx-1 text-gray-400">/</span>

        <span class="text-gray-800 font-semibold">
            {{ $ticketId ? $module : 'Nouveau ticket' }}
        </span>
    </x-slot>

    <div class="max-w-3xl mx-auto p-4">
    @if(session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-2">{{ session('message') }}</div>
    @endif

    <div class="bg-white shadow rounded p-4">
        <form wire:submit.prevent="save">

            <label class="block mt-2 font-semibold">Module</label>
            <input type="text" wire:model="module" class="w-full border rounded p-2">
            @error('module') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <label class="block mt-2 font-semibold">Scenario</label>
            <textarea wire:model="scenario" class="w-full border rounded p-2"></textarea>
            @error('scenario') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <label class="block mt-2 font-semibold">Etat</label>
            <select wire:model="etat" class="w-full border rounded p-2">
                <option value="en cours">en cours</option>
                <option value="terminé">terminé</option>
            </select>

            {{-- @if(auth()->user()->role === 'user') --}}
                <label class="block mt-2 font-semibold">Status</label>
                <select wire:model="status" class="w-full border rounded p-2">
                    <option value="ok">Ok</option>
                    <option value="à améliorer">À améliorer</option>
                    <option value="non ok">Non ok</option>
                </select>
            {{-- @endif --}}

            <label class="block mt-2 font-semibold">Commentaire</label>
            <textarea wire:model="commentaire" class="w-full border rounded p-2"></textarea>

            <label class="block mt-2 font-semibold">Pièce jointe(s)</label>
            @foreach($fichiersExistants as $key => $file)
                <div class="flex items-center justify-between space-x-2 mt-1">
                    <a href="{{ asset('storage/'.$file) }}" target="_blank" class="text-blue-600 underline text-sm">Voir fichier</a>
                    <button type="button" wire:click="removeExistingFile({{ $key }})" class="text-red-500 text-sm">Supprimer</button>
                </div>
            @endforeach

            <label for="fichiers" class="file-upload-area" 
                ondragover="this.classList.add('dragover')" 
                ondragleave="this.classList.remove('dragover')">
                Cliquez pour téléverser ou déposez vos fichiers ici (PNG, JPG, PDF)
                <input type="file" id="fichiers" wire:model="fichiers" multiple>
            </label>

            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
                {{ $ticketId ? 'Mettre à jour' : 'Créer Ticket' }}
            </button>
        </form>
    </div>
</div>

</x-layouts.app>
