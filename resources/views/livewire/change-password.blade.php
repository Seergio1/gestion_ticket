<x-layouts.app>
    <x-slot name="breadcrumbs">
        <a href="{{ route('projets.index') }}" class="text-blue-600 hover:underline">Projets</a>
        
        <span class="mx-1 text-gray-400">/</span>
        <span class="text-gray-800 font-semibold">
            {{-- {{ $projet ? 'Modifier le projet' : 'Nouveau projet' }} --}}
            Changement de mot de passe
        </span>
    </x-slot>

    <div class="max-w-3xl mx-auto p-4">
        @if(session()->has('message'))
            <div class="bg-green-100 p-2 rounded mb-2">{{ session('message') }}</div>
        @endif

        <div class="bg-white shadow rounded p-4">
            <form wire:submit.prevent="updatePassword" class="space-y-4">
                
                    <label class="block mt-2 font-semibold" for="current_password">Mot de passe actuel</label>
                    <input type="password" wire:model.defer="current_password" id="current_password" class="w-full border rounded p-2">
                    @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                

                
                    <label class="block mt-2 font-semibold" for="password">Nouveau mot de passe</label>
                    <input type="password" wire:model.defer="password" id="password" class="w-full border rounded p-2" autocomplete="off">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                

                
                    <label class="block mt-2 font-semibold" for="password_confirmation">Confirmer le mot de passe</label>
                    <input type="password" wire:model.defer="password_confirmation" id="password_confirmation" class="w-full border rounded p-2">
                

                <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
                    Changer le mot de passe
                </button>
            </form>
        </div>

        
    </div>


</x-layouts.app>

