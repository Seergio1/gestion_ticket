<x-layouts.app>
    <x-slot name="breadcrumbs">
        <a href="{{ route('projets.index') }}" class="text-blue-600 hover:underline">{{ $projet->nom }}</a>
        <span class="mx-1 text-gray-400">/</span>
        <span>Importation modules & fonctionnalités</span>
    </x-slot>

    <div class="max-w-2xl mx-auto p-4">
        @if(session()->has('message'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('message') }}</div>
        @endif

        @if(session()->has('error'))
            <div class="bg-red-100 text-red-800 p-2 rounded mb-4">{{ session('error') }}</div>
        @endif

        @error('file') 
            <div class="bg-red-100 text-red-800 p-2 rounded mb-4">{{ $message }}</div>
        @enderror

        <form wire:submit.prevent="import" enctype="multipart/form-data" class="bg-white shadow p-4 rounded">

            <label for="file" class="file-upload-area" 
                ondragover="this.classList.add('dragover')" 
                ondragleave="this.classList.remove('dragover')">
                Cliquez pour téléverser vos fichiers ici (.xlsx)
                <input type="file" id="file" wire:model="file">
            </label>
            @if ($this->fileName)
                <p class="mt-2 text-sm text-gray-600">
                    📄 <strong>Fichier sélectionné :</strong> {{ $this->fileName }}
                </p>
            @endif
           

            <button type="submit" wire:loading.attr="disabled"
                class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50">
                <span wire:loading.remove>Importer</span>
                <span wire:loading class="hidden">Importation en cours...</span>
            </button>
        </form>
    </div>
</x-layouts.app>
