<?php

namespace App\Http\Livewire;

use App\Models\Fonctionnalite;
use App\Models\Module;
use Livewire\Component;

class FonctionnaliteForm extends Component
{
    public $module_id;
    public $nom;
    public $description;

    public function mount($module_id)
    {
        $this->module_id = $module_id;
    }

    public function save()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Fonctionnalite::create([
            'nom' => $this->nom,
            'description' => $this->description,
            'module_id' => $this->module_id
        ]);

        session()->flash('message', 'Fonctionnalité créée avec succès.');
        $this->reset(['nom', 'description']);
    }

    public function render()
    {
        $module = Module::findOrFail($this->module_id);
        return view('livewire.fonctionnalite-form', compact('module'));
    }
}
