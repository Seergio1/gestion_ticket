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
    public $fonctionnalite_id;

    public function mount($module_id, $fonctionnalite_id = null)
    {
        $this->module_id = $module_id;
        $this->fonctionnalite_id = $fonctionnalite_id;
        if ($this->fonctionnalite_id) {
            $fonctionnalite = Fonctionnalite::findOrFail($this->fonctionnalite_id);
            $this->nom = $fonctionnalite->nom;
            $this->description = $fonctionnalite->description;
        }
    }

    public function save()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        if ($this->fonctionnalite_id) {
            // Modification
            $fonctionnalite = Fonctionnalite::findOrFail($this->fonctionnalite_id);
            $fonctionnalite->update([
                'nom' => $this->nom,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Fonctionnalité mise à jour avec succès.');
        } else {
            // Création
            Fonctionnalite::create([
                'nom' => $this->nom,
                'description' => $this->description,
                'module_id' => $this->module_id
            ]);
            session()->flash('message', 'Fonctionnalité créée avec succès.');
            $this->reset(['nom', 'description']);
        }
    }

    public function render()
    {
        $module = Module::findOrFail($this->module_id);
        return view('livewire.fonctionnalite-form', compact('module'));
    }
}
