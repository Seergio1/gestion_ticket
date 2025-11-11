<x-layouts.app>
    <x-slot name="breadcrumbs">
        <a href="{{ route('projets.index') }}" class="text-blue-600 hover:underline">Projets</a>
        <span class="mx-1 text-gray-400">/</span>
        <span class="text-gray-800 font-semibold">
            {{ $projet ? 'Modifier le projet' : 'Nouveau projet' }}
        </span>
    </x-slot>

    <div class="max-w-3xl mx-auto p-4">
        @if(session()->has('message'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-2">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white shadow rounded p-4">
            <form wire:submit.prevent="save">

                {{-- Nom du projet --}}
                <label class="block mt-2 font-semibold">Nom du projet</label>
                <input type="text" wire:model="nom" class="w-full border rounded p-2" placeholder="Nom du projet">
                @error('nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                {{-- Utilisateurs --}}
                <label class="block mt-2 font-semibold">Ajouter des utilisateurs</label>
                <select wire:model="utilisateurs" multiple class="w-full border rounded p-2">
                    @foreach($users as $user)
                        @if(!$projet || !in_array($user->id, $usersWithAccess->pluck('id')->toArray()))
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select>
                @error('utilisateurs') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                {{-- Utilisateurs déjà ajoutés --}}
                @if($projet && count($usersWithAccess) > 0)
                    <div class="mt-6 bg-gray-50 p-3 rounded ">
                        <p class="font-semibold mb-2">Utilisateurs ayant déjà accès :</p>
                        @foreach($usersWithAccess as $user)
                            <div class="flex justify-between items-center bg-white  px-3 py-1 rounded mb-1">
                                <span>{{ $user->name }}</span>
                                <button type="button" wire:click="removeUserFromDB({{ $user->id }})"
                                        class="text-red-600 hover:underline text-sm">
                                    Retirer l'accès
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Description --}}
                <label class="block mt-2 font-semibold">Description</label>
                <textarea wire:model="description" class="w-full border rounded p-2" placeholder="Description (optionnelle)"></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                {{-- Bouton dynamique --}}
                <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
                    {{ $projet ? 'Mettre à jour le projet' : 'Créer Projet' }}
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
